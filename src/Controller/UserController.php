<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ManuscriptType;
use App\Repository\UserRepository;
use App\Repository\ManuscriptRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(ManuscriptRepository $manuscriptRepository): Response
    {
        
        // récécupérer les livres dont l'id correspond à l'id du user
        $user = $this->getUser();
        // Récupération des identifants du user
        $user ->getUserIdentifier();

        // récupérer le manuscrit cliqué
        
        // lors de la requête on récupére l'id du user que l'on compare avec celui de l'auteur afin de récupérer les manuscrits de l'auteur, bien sélectionner la méthode findBy et non findOneBy
        $manuscripts = $manuscriptRepository->findBy([
            'author_id' => $user,
        ]);

        return $this->render('user/index.html.twig', [
            'manuscripts' => $manuscripts
        ]);

        //return new JsonResponse(array_map((function($m){return $m->getTitle();}), $manuscripts));
    }
}
