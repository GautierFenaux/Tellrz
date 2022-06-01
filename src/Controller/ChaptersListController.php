<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Repository\ChapterRepository;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ChaptersListController extends AbstractController
{
    #[Route('/chapters/list/{id}', name: 'chapters_list')]
    public function index(int $id, ChapterRepository $chapterRepository, ManuscriptRepository $manuscriptRepository): Response
    {
        // récupérer l'id qui se trouve dans l'url grâce à l'objet $request de type Request.
        // $manuscriptId = $request->get('id');
        $manuscript = $manuscriptRepository->find($id);
        
        $genres = $manuscript->getGenres()->getValues();

        //Ajouter un filtre pour avoir l'ordre des chap par position
        $chapters = $chapterRepository->findBy(['manuscriptId' => $id],['position' => 'ASC']);

        // donné à la propriété position la valeur du data-index ?
        // il me faut l'id de l'entité chapitre
        // je veux avoir la position des chapitres et pas la position du manuscript, récupérer la position de chaque chapitre et lui donner valeur avec ajax ?



        
        return $this->render('chapters_list/index.html.twig', [

            'chapters' => $chapters,
            'manuscript' => $manuscript,
            'genres' => $genres = implode(" ", $genres),
        ]);

    }

    #[Route('/chapters/reorder', name: 'chapters_draggable')]
    public function sortAction(Request $request, ChapterRepository $chapterRepository, EntityManagerInterface $entityManager)
    {
    
    // Récupération et décodage du json en tableau php  
    $positionArray = json_decode($request->getContent(), true);

    // Réinitialisation des données pour chaque chapitre, 
    // en récupérant celui-ci via la $value et en paramétrant la position avec la $key
    foreach ($positionArray as $key => $value) {
        $chapter = $chapterRepository->findOneById($value);
        $chapter->setPosition($key);
    }
    
    $entityManager->persist($chapter);
    $entityManager->flush();

    return new Response(
         Response::HTTP_OK
    );
 }
}