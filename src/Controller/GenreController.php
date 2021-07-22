<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\GenreSearch;
use App\Form\GenreSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre", name="genre")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new GenreSearch();
        $form = $this->createForm(GenreSearchType::class, $search);
        $form->handleRequest($request);
        $genres = $this->getDoctrine()->getRepository(Genre::class)->findAllVisible($search);
        return $this->render('cineland/genre/index.html.twig', [
            'controller_name' => 'GenreController',
            'genres' => $genres,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/ajouter", name="ajouter_genre")
     * @param Request $request
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createFormBuilder($genre)
            ->add('nom', TextType::class)
            ->add('ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();
            $this->addFlash('success', 'Genre ajouté avec succès');
            return $this->redirectToRoute('genre');
        }
        return $this->render('cineland/genre/ajouter.html.twig', [
            'controller_name' => 'GenreController',
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/supprimer/{id}", name="supprimer_genre")
     * @param $id
     * @return RedirectResponse
     */
    public function supprimer($id): RedirectResponse
    {
        $genre = $this->getDoctrine()->getRepository(Genre::class)->find($id);
        if ($genre) {
            if (count($genre->getFilms()) == 0) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($genre);
                $entityManager->flush();
                $this->addFlash('success', 'Genre supprimé avec succès');
            }
        }
        return $this->redirectToRoute("genre");
    }
}
