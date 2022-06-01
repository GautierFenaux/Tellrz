<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Entity\Reporting;
use App\Form\ReportingType;
use App\Repository\ChapterRepository;
use App\Repository\ReportingRepository;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/reporting')]
class AdminReportingController extends AbstractController
{
    #[Route('/', name: 'admin_reporting_index', methods: ['GET', 'POST'])]
    public function index(ReportingRepository $reportingRepository, Request $request): Response 
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        

        $reportings = $reportingRepository->findSearch($data);
        
        
        
        return $this->render('admin_reporting/index.html.twig', [
            'reportings' => $reportings,
            'form' => $form->createView(),
        
        ]);
    }

    #[Route('/new/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'admin_reporting_new', methods: ['GET', 'POST'])]
    public function new(int $manuscriptId, int $chapterId, Request $request, EntityManagerInterface $entityManager, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {
        // Au moment de la création du reporting liaison avec le manuscript et le chapter.
        $reporting = new Reporting();
        $form = $this->createForm(ReportingType::class, $reporting);
        $form->handleRequest($request);

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

        return $this->renderForm('admin_reporting/new.html.twig', [
            'reporting' => $reporting,
            'form' => $form,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId,
        ]);
    }

    #[Route('/{id}/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'admin_reporting_show', methods: ['GET'])]
    public function show(Reporting $reporting, int $manuscriptId, int $chapterId, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {
        $manuscript = $manuscriptRepository->findOneBy(['id' => $manuscriptId]);
        $chapter = $chapterRepository->findOneBy(['id' => $chapterId]);

        return $this->render('admin_reporting/show.html.twig', [
            'reporting' => $reporting,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId,
        ]);
    }

    #[Route('/{id}/edit/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'admin_reporting_edit', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('admin_reporting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_reporting/edit.html.twig', [
            'reporting' => $reporting,
            'form' => $form,
            'manuscriptId' => $manuscriptId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('/{id}', name: 'admin_reporting_delete', methods: ['POST'])]
    public function delete(Request $request, Reporting $reporting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reporting->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reporting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_reporting_index', [], Response::HTTP_SEE_OTHER);
    }
}
