<?php

/**
 * Espece controller, created automatically by symfony CRUD and modified the index pages
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

namespace App\Controller;

use App\Entity\Echouage;
use App\Form\EchouageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/echouage")
 */
class EchouageController extends AbstractController
{
    /**
     * @Route("/", name="echouage_index", methods={"GET"})
     * Automatically created by crud but altered : this route used to send all of the echouages in the database, it made the web browser slow down because there are some 4k-5k entries, not a sane approach
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute("echouage_index_page", ['page' => 0]);
    }

    /**
     * @Route("/page/{page}", name="echouage_index_page", methods={"GET"})
     * Returns maximim 50 entries of echouage corresponding to the given page number, ie page 0 is entry 0-49, page 1 is entry 50-99....
     */
    public function index_page(EntityManagerInterface $entityManager, int $page): Response
    {
        // Check if give page number is too hight or not positive, redirect if it's the case
        $pages_count = $entityManager->getRepository(Echouage::class)->pagesCount(50);

        if ($pages_count == 0) {
            return $this->render('echouage/index.html.twig', [
                'echouages' => [],
            ]);
        } elseif ($page >= $pages_count) {
            return $this->redirectToRoute('echouage_index_page', ['echouages' => [], 'page' => $pages_count - 1, 'page_count' => $pages_count]);
        } elseif ($page < 0) {
            return $this->redirectToRoute('echouage_index_page', ['echouages' => [], 'page' => 0, 'page_count' => $pages_count]);
        }

        // Safe from sql injection, page is a int
        $echouages = $entityManager
            ->getRepository(Echouage::class)
            ->findPage($page, 50);

        return $this->render('echouage/index.html.twig', [
            'echouages' => $echouages,
            'page' => $page,
            'page_count' => $pages_count
        ]);
    }

    /**
     * @Route("/new", name="echouage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $echouage = new Echouage();
        $form = $this->createForm(EchouageType::class, $echouage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echouage);
            $entityManager->flush();

            return $this->redirectToRoute('echouage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('echouage/new.html.twig', [
            'echouage' => $echouage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="echouage_show", methods={"GET"})
     */
    public function show(Echouage $echouage): Response
    {
        return $this->render('echouage/show.html.twig', [
            'echouage' => $echouage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="echouage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Echouage $echouage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EchouageType::class, $echouage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('echouage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('echouage/edit.html.twig', [
            'echouage' => $echouage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="echouage_delete", methods={"POST"})
     */
    public function delete(Request $request, Echouage $echouage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $echouage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echouage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('echouage_index', [], Response::HTTP_SEE_OTHER);
    }
}
