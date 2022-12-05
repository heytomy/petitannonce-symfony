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

    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $totalAnnonce = $annonceRepository->findTotalNotSold();
        $perPage = 21;
        $totalPages = ceil($totalAnnonce / $perPage);

        $page = $request->get('page');

        if ($page === null || $page > $totalPages || $page < 1) {
            $page = 1;
        }
        $annonces = $annonceRepository->findAllNotSoldPaginate($page, $perPage);
        $annonces = $annonceRepository->findAllNotSold();

        return $this->render('annonce/index.html.twig', [
            'current_menu' => 'app_annonce_index',
            'annonces' => $annonces,
            'total_pages' => $totalPages,
            'page' => $page,
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

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('app_admin_annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
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
}
