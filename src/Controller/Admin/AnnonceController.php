<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;

# note la route qui sera préfixée pour chaque route du contrôleur
/**
 * @Route("/admin")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce")
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        $annonces = $annonceRepository->findAll();
        return $this->render('admin/annonce/index.html.twig', [
            'data_class' => Annonce::class,
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/annonce/{id}/edit", methods={"POST", "GET"})
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_annonce_index');
        }

        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonce/{id}", methods="DELETE")
     */

    public function delete(Annonce $annonce, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->get('_token'))) {
            $em->remove($annonce);
            $em->flush();
        }
        return $this->redirectToRoute('admin_annonce_index');
    }
}
