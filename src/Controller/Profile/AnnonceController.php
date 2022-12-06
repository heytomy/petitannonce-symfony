<?php

namespace App\Controller\Profile;

use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/profile")
 */
class AnnonceController extends AbstractController
{
        /**
     * @Route("/annonce")
     */
    public function index()
    {
        $user = $this->getUser();
        return $this->render('profile/annonce/index.html.twig', [
            'annonce' => $user->getAnnonces(),
        ]);
    }
    
    public function edit(Annonce $annonce, EntityManagerInterface $em, Request $request)
    {
        if ($annonce->getUser() !== $user) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette ressource');
            return $this->redirectToRoute('profile');
        }
    }

}