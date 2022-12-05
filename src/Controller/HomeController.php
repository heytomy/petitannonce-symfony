<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnonceRepository;


class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        $annonces = $annonceRepository->findLatestNotSold();
        return $this->render('home/index.html.twig',[
            'annonces' => $annonces
        ]);
    }
}
