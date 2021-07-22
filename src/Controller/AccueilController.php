<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil(): Response
    {
        return $this->render('cineland/accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function start(): RedirectResponse
    {
        return $this->redirectToRoute('accueil');
    }
}
