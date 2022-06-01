<?php

namespace App\Controller;

use App\Entity\Reporting;
use App\Form\ReportingType;
use App\Repository\ChapterRepository;
use App\Repository\ManuscriptRepository;
use App\Repository\ReportingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reporting')]
class ReportingController extends AbstractController
{
    #[Route('/', name: 'reporting_index', methods: ['GET', 'POST'])]
    public function index(ReportingRepository $reportingRepository): Response
    {
        return $this->render('reporting/index.html.twig', [
            'reportings' => $reportingRepository->findAll(),
        ]);
    }

    #[Route('/new/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'reporting_new', methods: ['GET', 'POST'])]
    public function new(int $manuscriptId, int $chapterId, Request $request, EntityManagerInterface $entityManager, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {
        // Au moment de la création du reporting lié avec le manuscript et le chapter.
        $reporting = new Reporting();
        $form = $this->createForm(ReportingType::class, $reporting);
        $form->handleRequest($request);

        // Est-ce que je peux récupèrer l'adresse mail de l'auteur à partir de du manuscript ?
        $manuscript = $manuscriptRepository->findOneBy(['id' => $manuscriptId]);
        $author = $manuscript->getAuthorId();
        $chapter = $chapterRepository->findOneBy(['id' => $chapterId]);
        $reporting->setManuscript($manuscript);
        $reporting->setChapter($chapter);
        $reporting->setUser($author);
        


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reporting);
            $entityManager->flush();

            $this->addFlash('success', 'Votre signalement a bien été pris en compte.');

            // return $this->redirectToRoute('admin_reporting_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('manuscript_chapter', [
                    'manuscriptId' =>  $manuscriptId,
                    'chapterId' => $chapterId,
            ], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('reporting/new.html.twig', [
            'reporting' => $reporting,
            'form' => $form,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId,
        ]);
    }

    #[Route('/{id}/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'reporting_show', methods: ['GET'])]
    public function show(Reporting $reporting, int $manuscriptId, int $chapterId, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {
        $manuscript = $manuscriptRepository->findOneBy(['id' => $manuscriptId]);
        $chapter = $chapterRepository->findOneBy(['id' => $chapterId]);

        return $this->render('reporting/show.html.twig', [
            'reporting' => $reporting,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId,
        ]);
    }

    #[Route('/{id}/edit/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'reporting_edit', methods: ['GET', 'POST'])]
    public function edit(int $manuscriptId, int $chapterId, Request $request, Reporting $reporting, EntityManagerInterface $entityManager, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {
        $form = $this->createForm(ReportingType::class, $reporting);
        $form->handleRequest($request);

        $manuscript = $manuscriptRepository->findOneBy(['id' => $manuscriptId]);
        $chapter = $chapterRepository->findOneBy(['id' => $chapterId]);

        // $manuscriptId = $reporting->getManuscript();
        // $chapterId = $reporting->getChapter();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reporting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reporting/edit.html.twig', [
            'reporting' => $reporting,
            'form' => $form,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('/{id}', name: 'reporting_delete', methods: ['POST'])]
    public function delete(Request $request, Reporting $reporting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reporting->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reporting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reporting_index', [], Response::HTTP_SEE_OTHER);
    }
}
