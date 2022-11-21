<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Bienvenue sur Duckzon',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem quam cum corrupti modi cupiditate nostrum odit illo veniam, nulla neque officia expedita rerum, aliquid libero incidunt rem iusto reprehenderit maxime!',
            'date' => new \DateTime(),
        ]);
    }
}
