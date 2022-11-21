<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    public function index(ManagerRegistry $doctrine)
    {
        $repository = $doctrine()->getRepository(Annonce::class);
    }
    /**
 * @Route("/annonce/new")
 */
public function new(ManagerRegistry $doctrine){
    $annonce = new Annonce();
    $annonce
        ->setTitle('Ma collection de canard vivant')
        ->setDescription('Vends car plus d\'utilité')
        ->setPrice(10)
        ->setStatus(Annonce::STATUS_BAD)
        ->setSold(false)
    ;

    // On récupère l'EntityManager 
    $em = $doctrine->getManager();
    // On « persiste » l'entité
    $em->persist($annonce);
    // On envoie tout ce qui a été persisté avant en base de données
    $em->flush();
}
}