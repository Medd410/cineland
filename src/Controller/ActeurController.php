<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\ActeurSearch;
use App\Entity\Film;
use App\Entity\Genre;
use App\Form\ActeurSearchType;
use App\Form\ActeurType;
use App\Repository\ActeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeurController extends AbstractController
{
    /**
     * @Route("/acteur", name="acteur")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new ActeurSearch();
        $form = $this->createForm(ActeurSearchType::class, $search);
        $form->handleRequest($request);
        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)->findAllVisible($search);
        return $this->render('cineland/acteur/index.html.twig', [
            'controller_name' => 'ActeurController',
            'acteurs' => $acteurs,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/acteur/detail/{id}", name="detail_acteur")
     * @param $id
     * @return Response
     */
    public function detail($id): Response
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur)
            return $this->redirectToRoute("acteur");
        $form = $this->createFormBuilder()
            ->add('ageMin', IntegerType::class, [
                'label' => 'Augmenter l\'age',
                'required' => false
            ])
            ->setAction($this->generateUrl('augmenter_acteur', ['id' => $acteur->getId()]))
            ->getForm();
        return $this->render('cineland/acteur/acteur.html.twig', [
            'controller_name' => 'ActeurController',
            'acteur' => $acteur,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/acteur/ajouter", name="ajouter_acteur")
     * @param Request $request
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        $acteur = new Acteur();
        $form = $this->createForm(ActeurType::class, $acteur)
            ->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($acteur);
            $entityManager->flush();
            $this->addFlash("success", "Acteur ajouté avec succès");
            return $this->redirectToRoute('acteur');
        }
        return $this->render('cineland/acteur/ajouter.html.twig', [
            'controller_name' => 'ActeurController',
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/acteur/modifier/{id}", name="modifier_acteur")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifier(Request $request, $id): Response
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        $form = $this->createForm(ActeurType::class, $acteur)
            ->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($acteur);
            $entityManager->flush();
            $this->addFlash("success", "Acteur modifié avec succès");
            return $this->redirectToRoute('acteur');
        }
        return $this->render('cineland/acteur/modifier.html.twig', [
            'controller_name' => 'ActeurController',
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/acteur/supprimer/{id}", name="supprimer_acteur")
     * @param $id
     * @return RedirectResponse
     */
    public function supprimer($id): RedirectResponse
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if ($acteur) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($acteur);
            $entityManager->flush();
            $this->addFlash("success", "Acteur supprimé avec succès");
        }
        return $this->redirectToRoute("acteur");
    }

    /**
     * @Route("/acteur/augmenter/{id}", name="augmenter_acteur")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function augmenter(Request $request, $id): RedirectResponse
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur)
            return $this->redirectToRoute("acteur");
        $add = $request->request->get('form')['ageMin'];
        if (!$add) {
            $add = 1;
        }
        if ($add < 0)
            return $this->redirectToRoute("acteur");
        $films = $acteur->getFilms();
        foreach ($films as $film) {
            $film->setAgeMinimal($film->getAgeMinimal() + $add);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("acteur");
    }

    /**
     * @Route("/acteur/filmJoues", name="film_joues_acteur")
     * @return Response
     */
    public function filmJoues(): Response
    {
        $result = [];
        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)->findAll();
        for ($i = 0; $i < count($acteurs); $i++)
        {
            $result[$i] = ['id' => $acteurs[$i]->getId(), 'nomPrenom' => $acteurs[$i]->getNomPrenom(), 'films' => []];
            $films = $this->getDoctrine()->getRepository(Film::class)->findByActeur($acteurs[$i]->getId());
            foreach ($films as $film) {
                $result[$i]['films'][] = $film;
            }
        }
        return $this->render('cineland/acteur/films.html.twig', [
            'controller_name' => 'ActeurController',
            'acteurs' => $result
        ]);
    }

    /**
     * @Route("/acteur/genreJoues", name="genre_joues_acteur")
     * @return Response
     */
    public function genreJoues(): Response
    {
        $result = [];
        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)->findAll();
        for ($i = 0; $i < count($acteurs); $i++)
        {
            $result[$i] = ['id' => $acteurs[$i]->getId(), 'nomPrenom' => $acteurs[$i]->getNomPrenom(), 'genres' => []];
            $genres = $this->getDoctrine()->getRepository(Genre::class)->findByActeur($acteurs[$i]->getId());
            foreach ($genres as $genre) {
                $result[$i]['genres'][] = $genre['nom'];
            }
        }
        return $this->render('cineland/acteur/genres.html.twig', [
            'controller_name' => 'ActeurController',
            'acteurs' => $result
        ]);
    }
}
