<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\ManuscriptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/genre', name: 'genre')]
    public function index(GenreRepository $genreRepository): Response
    {

        $genre = $genreRepository->findAll();

        return $this->render('genre/index.html.twig', [
            'genre' => $genre,
        ]);
    }

    // Changer le slug avec un nom de genre ?
    #[Route('/genre/{slug}', name: 'genre-detail') ]

    public function findManuscriptByGenre(string $slug, GenreRepository $genreRepository, ManuscriptRepository $manuscriptRepository): Response
    {
        
        $genre = $genreRepository->findOneBy(['slug' => $slug]);
        // Faire une requête qui récupère tous les livres qui ont l'id du genre passer en url 
        // comment faire une requête dans la table de relation ?
        
        $manuscripts = $manuscriptRepository->findByGenre($genre->getId());
        // On déclenche le rendu d'une vue (template) en lui passant les données du livre
        return $this->render('genre/detail.html.twig', [
            "genre" => $genre,
            "manuscripts" => $manuscripts
        ]);
    }
}

