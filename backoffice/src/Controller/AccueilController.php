<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Echouage;
use App\Form\AccueilType;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function new(Request $request): Response
    {
        $echouage = new Echouage();
        $form = $this->createForm(AccueilType::class, $echouage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) { 

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($echouage);
            $entityManager->flush();

            return $this->redirectToRoute('');
        }

        return $this->render('accueil/index.html.twig' , [
            'echouages' => $echouage,
            'form' => $form->createView(),
        ]);
    }
}
