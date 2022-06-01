<?php

namespace App\Controller;


use App\Entity\Genre;
use DateTimeImmutable;
use App\Entity\Question;
use App\Entity\Manuscript;
use App\Form\ManuscriptType;
use Doctrine\ORM\Query\Expr\Math;
use App\Repository\QuestionRepository;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/edition/tools')]
class EditionToolsController extends AbstractController
{
    #[Route('/', name: 'edition_tools_index', methods: ['GET'])]
    public function index(ManuscriptRepository $manuscriptRepository): Response
    {
        return $this->render('edition_tools/index.html.twig', [
            'manuscripts' => $manuscriptRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'edition_tools_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $manuscript = new Manuscript();
        // On crée un formulaire via la méthode createForm issue de AbstractController
        $form = $this->createForm(ManuscriptType::class, $manuscript);
        $form->handleRequest($request);
        // récupèrer l'id du user et on le lie à l'entité manuscript.
        $user = $this->getUser();
        $userIdentifier = $user->getUserIdentifier();
        
        if ($form->isSubmitted() && $form->isValid()) {
        /*  récupérer un objet Question qui se trouve dans l'objet $manuscript créer à partir du formulaire , pr le lier dans une relation ManyToMany 
        à $manuscript avec la méthode addQuestion de l'entité Manuscript */   

            // Créer la logique pr aller chercher la question dynamiquement, prendre en compte la possibilité qu'elle puisse être nulle
            //Returns the normalized data of the field, used as internal bridge between model data and view data.
            $data = $form->getData();
            // J'utilise la méthode getQuestions de l'objet Manuscript créé lors de la configuration de la relation entre l'entité Question et l'entité Manuscript
            // qui me renvoie un objet de type ArrayCollection dans lequel sont stockées les questions (sous forme d'array) sélectionnées par l'auteur sous la forme de l'objet de type Question
            // J'utilise la méthode getValues propre à cet objet (de type ArrayCollection) pour obtenir les éléments contenus dans l'array
            $questions = $data->getQuestions()->getValues();
            

            
            // J'utilise une logique pour traiter  les questions si elles existent, 
            //alors addQuestion prend l'objet de type Question pris en argument afin de créer la relation avec l'entité Manuscript et permettre la relation dans la bdd.
            // if($questions != null) 
            // {   
                
            //     foreach ($questions as $question) 
            //     {
            //         $manuscript = $manuscript->addQuestion($question); 
            //     }
            // } 

            // On lie le manuscrit à son auteur avec la méthode setAuthorid
            $manuscript = $manuscript->setAuthorId($user); 
            $manuscript = $manuscript->setAuthor($userIdentifier);

            $manuscript->setUpdatedAt(new DateTimeImmutable());
            $entityManager->persist($manuscript);
            
            //Pourquoi l'id est paramétré au moment du flush ?
            $entityManager->flush();
            

            // Passer le nouveau manuscrit dans la route pour créer un chapitre
            return $this->redirectToRoute('chapter_new', [ 'manuscriptId'=>$manuscript->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edition_tools/new.html.twig', [
            'manuscript' => $manuscript,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'edition_tools_show', methods: ['GET'])]
    public function show(Manuscript $manuscript): Response
    {
        return $this->render('edition_tools/show.html.twig', [
            'manuscript' => $manuscript,
        ]);
    }

    #[Route('/{id}/edit', name: 'edition_tools_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Manuscript $manuscript, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ManuscriptType::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('edition_tools_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edition_tools/edit.html.twig', [
            'manuscript' => $manuscript,
            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'edition_tools_delete', methods: ['POST'])]
    public function delete(Request $request, Manuscript $manuscript, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manuscript->getId(), $request->request->get('_token'))) {
            $entityManager->remove($manuscript);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edition_tools_index', [], Response::HTTP_SEE_OTHER);
    }
}
