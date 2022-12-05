<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;

/**
 * @Route("/profile")
 */
class AnnonceController extends AbstractController
{
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'annonces' => $user->getAnnonces(), // récupère toutes les annonces de l'utilisateur
        ]);
    }
}