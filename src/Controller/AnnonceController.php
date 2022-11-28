<?php

namespace App\Controller;

use DateTime;
use App\Entity\Annonce;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;

class AnnonceController extends AbstractController
{
    public function index(AnnonceRepository $annonceRepository)
    {
        $annonces = $annonceRepository->findAllNotSold();
    
        return $this->render('annonce/index.html.twig', [
            'current_menu' => 'app_annonce_index',
            'annonces' => $annonces,
        ]);
    }
    
    /**
    * @Route("/annonce/new", name="new_annonce")
    */

    public function new(ManagerRegistry $doctrine){
    $annonce = new Annonce();
    $annonce
        ->setTitle('Ma collection de canard vivant')
        ->setDescription('Vends car plus d\'utilité')
        ->setPrice(10)
        ->setStatus(Annonce::STATUS_BAD)
        ->setSold(false)
        ->setCreatedAt(new DateTime())
        ;

    // On récupère l'EntityManager 
    $em = $doctrine->getManager();
    // On « persiste » l'entité
    $em->persist($annonce);
    // On envoie tout ce qui a été persisté avant en base de données
    $em->flush();
    
    die ('Annonce bien créée');
}
    public function show(int $id, AnnonceRepository $annonceRepository): Response{

    $annonce = $annonceRepository->find($id);

    if (!$annonce) {
        return $this->createNotFoundException();
    }
    return $this->render('annonce/show.html.twig', [
        'annonce' => $annonce,
    ]);

}

    /**
     * @Route(
    *  "/annonce/{slug}-{id}", 
    *  requirements={"slug": "[a-z0-9\-]*", "id": "\d+"}
     * )
     * @return Response
    */
    public function showBySlug(string $slug, int $id, AnnonceRepository $annonceRepository): Response{
    $annonce = $annonceRepository->findOneBy([
        'slug' => $slug,
        'id' => $id
    ]);

    if (!$annonce) {
        return $this->createNotFoundException();
    }

    return $this->render('annonce/show.html.twig', [
        'annonce' => $annonce,
    ]);
}
}

    
