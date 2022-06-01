<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Form\ChapterType;
use App\Entity\Manuscript;
use App\Repository\ChapterRepository;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/chapter')]
class ChapterController extends AbstractController
{
    #[Route('/', name: 'chapter_index', methods: ['GET'])]
    public function index(ChapterRepository $chapterRepository): Response
    {
        return $this->render('chapter/index.html.twig', [
            'chapters' => $chapterRepository->findAll(),
        ]);
    }
    // Ajouter {manuscript_id} devant new ? Pas obligatoire mais plus propre
    #[Route('/{manuscriptId}/new', name: 'chapter_new', methods: ['GET', 'POST'])]
    // #[ParamConverter('manuscript', class: 'App\Entity\Manuscript')]
    public function new(Request $request, EntityManagerInterface $entityManager, ManuscriptRepository $manuscriptRepository): Response
    {
        //Ajouter les logiques pr récupérer les Id des user et des manuscrits. 
        $chapter = new Chapter();
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        
        $user = $this->getUser();
        
        //Faire une requête pour aller récupérer le dernier id du manuscrit
        $manuscriptId = $request->get('manuscriptId');
        $manuscript = $manuscriptRepository->findOneBy(['id'=> $manuscriptId]);

        $chapter->setAuthorId($user);
        $chapter->setManuscriptId($manuscript);
        
    
        if ($form->isSubmitted() && $form->isValid()) {
            //Permet de passer la position du chapitre lors de la création de chapitre
            $chapter->setPosition(count($manuscript->getChapters()) +1);
            $entityManager->persist($chapter);
            $entityManager->flush();
            

            return $this->redirectToRoute('chapters_list', ['id'=> $manuscript->getId()
        ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chapter/new.html.twig', [
            'chapter' => $chapter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'chapter_show', methods: ['GET'])]
    public function show(Chapter $chapter): Response
    {
        return $this->render('chapter/show.html.twig', [
            'chapter' => $chapter,
        ]);
    }

    #[Route('/{id}/edit', name: 'chapter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapter $chapter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Faire une redirection vers l'url approprié, mais c'est lequel, c'est la même page ?
            return $this->redirectToRoute('chapter_index', [], Response::HTTP_SEE_OTHER);
        }

        // $message = 'changement pris en compte.';


        return $this->renderForm('chapter/edit.html.twig', [
            'chapter' => $chapter,
            'form' => $form,
            // 'message' => $message
        ]);
    }

    // Ajouter méthode pour traiter le form


    #[Route('/{id}', name: 'chapter_delete', methods: ['POST'])]
    public function delete(Request $request, Chapter $chapter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chapters_list', ['id'=> $chapter->getManuscriptId()->getId()
        ], Response::HTTP_SEE_OTHER);
    }
}
