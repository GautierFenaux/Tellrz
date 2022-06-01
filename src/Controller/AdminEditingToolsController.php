<?php

namespace App\Controller;

use App\Entity\Manuscript;
use App\Form\Manuscript1Type;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/editing/tools')]
class AdminEditingToolsController extends AbstractController
{
    #[Route('/', name: 'admin_editing_tools_index', methods: ['GET'])]
    public function index(ManuscriptRepository $manuscriptRepository): Response
    {
        return $this->render('admin_editing_tools/index.html.twig', [
            'manuscripts' => $manuscriptRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_editing_tools_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $manuscript = new Manuscript();
        $form = $this->createForm(Manuscript1Type::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($manuscript);
            $entityManager->flush();

            return $this->redirectToRoute('admin_editing_tools_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_editing_tools/new.html.twig', [
            'manuscript' => $manuscript,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_editing_tools_show', methods: ['GET'])]
    public function show(Manuscript $manuscript): Response
    {
        return $this->render('admin_editing_tools/show.html.twig', [
            'manuscript' => $manuscript,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_editing_tools_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Manuscript $manuscript, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Manuscript1Type::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_editing_tools_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_editing_tools/edit.html.twig', [
            'manuscript' => $manuscript,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_editing_tools_delete', methods: ['POST'])]
    public function delete(Request $request, Manuscript $manuscript, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manuscript->getId(), $request->request->get('_token'))) {
            $entityManager->remove($manuscript);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_editing_tools_index', [], Response::HTTP_SEE_OTHER);
    }
}
