<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AnnonceType;
use Knp\Component\Pager\PaginatorInterface;

class AnnonceController extends AbstractController
{  
    #[Route('/annonces', name: 'app_annonces')]
    public function index(Request $request, PaginatorInterface $paginator, AnnonceRepository $annonceRepository): Response
    {
        $annonces = $paginator->paginate(
            $annonceRepository->findAllNotSoldQuery(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /**
     * @Route("/annonce/new")
     *
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {

        $annonce = new Annonce();
        $annonce->setUser($this->getUser());
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('app_profile_annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'curent_page' => $annonce,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonce/{id}/edit", methods={"POST", "GET"})
     */
    public function edit(Annonce $annonce, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            // ajout du message flash
            $this->addFlash('success', 'Annonce modifiée avec succès');
            return $this->redirectToRoute('app_profile_annonce_index');
        }

        return $this->render('annonce/edit.html.twig', [
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
        return $this->redirectToRoute('app_profile_annonce_index');
    }

    public function show(int $id, AnnonceRepository $annonceRepository): Response
    {
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
     *  "/annonces/{slug}-{id}", 
     *  requirements={"slug": "[a-z0-9\-]*", "id": "\d+"}
     * )
     * @return Response
     */
    public function showBySlug(Annonce $annonce, $slug): Response
    {
        return $this->render('annonce/show.html.twig', [
            'current_menu' => 'app_annonce_index',
            'annonce' => $annonce, // Symfony fait le find à notre place grâce à l'injection et l'id
        ]);
    }

    public function adminDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }
}
