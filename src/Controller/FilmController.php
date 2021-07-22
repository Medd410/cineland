<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\FilmSearch;
use App\Form\FilmSearchType;
use App\Form\FilmType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/film", name="film")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);
        $films = $this->getDoctrine()->getRepository(Film::class)->findAllVisible($search);
        return $this->render('cineland/film/index.html.twig', [
            'controller_name' => 'FilmController',
            'films' => $films,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/film/detail/{id}", name="detail_film")
     * @param $id
     * @return Response
     */
    public function detail($id): Response
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if (!$film)
            return $this->redirectToRoute("film");
        return $this->render('cineland/film/film.html.twig', [
            'controller_name' => 'FilmController',
            'film' => $film
        ]);
    }

    /**
     * @Route("/film/ajouter", name="ajouter_film")
     * @param Request $request
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film)
            ->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();
            $this->addFlash('success', 'Film ajouté avec succès');
            return $this->redirectToRoute('film');
        }
        return $this->render('cineland/film/ajouter.html.twig', [
            'controller_name' => 'FilmController',
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/film/modifier/{id}", name="modifier_film")
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function modifier(Request $request, $id)
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        $form = $this->createForm(FilmType::class, $film)
            ->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();
            $this->addFlash('success', 'Film modifié avec succès');
            return $this->redirectToRoute('film');
        }
        return $this->render('cineland/film/modifier.html.twig', [
            'controller_name' => 'FilmController',
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/film/supprimer/{id}", name="supprimer_film")
     * @param $id
     * @return Response
     */
    public function supprimer($id): Response
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if ($film) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
            $this->addFlash('success', 'Film supprimé avec succès');
        }
        return $this->redirectToRoute("film");
    }

    /**
     * @Route("/film/augmenter/{id}", name="augmenter_note_film")
     * @param $id
     * @return RedirectResponse
     */

    public function augmenterNote($id): RedirectResponse
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if ($film) {
            if ($film->getNote() < 20) {
                $film->setNote($film->getNote() + 1);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute("detail_film", ['id' => $film->getId()]);
        }
        return $this->redirectToRoute("film");
    }

    /**
     * @Route("/film/reduire/{id}", name="reduire_note_film")
     * @param $id
     * @return RedirectResponse
     */
    public function reduireNote($id): RedirectResponse
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if ($film) {
            if ($film->getNote() > 0) {
                $film->setNote($film->getNote() - 1);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute("detail_film", ['id' => $film->getId()]);
        }
        return $this->redirectToRoute("film");
    }
}
