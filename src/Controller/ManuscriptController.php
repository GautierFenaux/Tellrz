<?php

namespace App\Controller;

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

class ManuscriptController extends AbstractController
{
    // comment fait symfony pr récupérer le GET ?
    #[Route('/manuscript/{id}', name: 'manuscript')]
    public function index(int $id, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository): Response
    {

        $manuscript = $manuscriptRepository->findOneBy(['id' => $id]);
        $genres = $manuscript->getGenres()->getValues();
        $genres = implode(" ", $genres);
        
        
        // récupérer tous les chapitres par leur position grâce à l'id passé en url.
        $chapters = $chapterRepository->findBy(['manuscriptId' => $id], ['position' => 'ASC']);


        return $this->render('manuscript/index.html.twig', [
            'chapters' => $chapters,
            'manuscript' => $manuscript,
            'genres' => $genres,
            
        ]);
    }

    #[Route('/manuscript/{manuscriptId}/chapter/{chapterId}', name: 'manuscript_chapter', methods: ['GET', 'POST'])]

    public function showChapter(int $manuscriptId, int $chapterId, ManuscriptRepository $manuscriptRepository, ChapterRepository $chapterRepository, ReportingRepository $reportingRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $reportings = $reportingRepository->findAll();
        $manuscript = $manuscriptRepository->findOneBy(['id' => $manuscriptId]);
        $chapter = $chapterRepository->findOneBy(['id' => $chapterId]);
        // Récupérer tous les chapitres qui ont le manuscript_id
        //Récupération de tous les chapitres qui ont l'id du manuscript passé en url
        $chapters = $chapterRepository->findBy(['manuscriptId' => $manuscriptId]);
        
        
        // trouver dans le tableau celui qui correspond avec l'id du manuscrit passé en url, récupérer l'index de celui qui correspond et lui ajouter 1
        //pr récupèrer l'index du prochain, comment retourner l'app entity chapter avec son index qui a l'id qui correspond
        // retourne l'index du chapitre qui correspond 
        $currentChapterIndex = array_search($chapter, $chapters); 
        // Boucle pour itérer le tableau puis on vérifie si il y a un prochain chapitre si oui $nextChapter prend une valeur sinon il est nul et permet d'afficher FIN à la place du btn dans la vue TWIG
        for($i = 0; $i < count($chapters); $i++ ) {
            if(isset($chapters[$currentChapterIndex + 1])) {
                $nextChapter = $chapters[$currentChapterIndex + 1];
            } else {
                $nextChapter = null;
            };
        }

        
        // $referer = $request->headers->get('referer');
        // return new RedirectResponse($referer);


        return $this->render('manuscript/manuscript_chapter.html.twig', [
            'chapter' => $chapter,
            'chapters' => $chapters,
            'manuscript' => $manuscript,
            'next_chapter' => $nextChapter,
            'reportings' => $reportings,
            'chapter_id' =>  $chapterId,
            'manuscript_id' =>  $manuscriptId
        ]);
    }
}