<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InitController extends AbstractController
{
    /**
     * @Route("/init", name="init")
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function init(ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $arr_genre = [
            'animation',
            'policier',
            'drame',
            'comédie',
            'X',
            "science-fiction"
        ];

        $arr_acteur = [
            ['Galabru Michel', '27/10/1922', 'france'],
            ['Deneuve Catherine', '22/10/1943', 'france'],
            ['Depardieu Gérard', '27/12/1948', 'russie'],
            ['Lanvin Gérard', '21/06/1950', 'france'],
            ['Désiré Dupond', '23/12/2001', 'groland'],
            ['Mila Kunis', '14/08/1983', 'amérique'],
            ['Channing Tatum', '26/04/1980', 'amérique'],
            ['Sean Bean', '17/04/1959', 'royaume-uni']
        ];

        $arr_film = [
            ['Astérix aux jeux olympiques', 117, '20/01/2008', 8, 0, 'animation', []],
            ['Le Dernier Métro', 131, '17/09/1980', 15, 12, 'drame', ['Deneuve Catherine', 'Depardieu Gérard']],
            ['Le choix des armes', 135, '19/10/1981', 13, 18, 'policier', ['Deneuve Catherine', 'Depardieu Gérard', 'Lanvin Gérard']],
            ['Les Parapluies de Cherbourg', 91, '19/02/1964', 9, 0, 'drame', ['Deneuve Catherine']],
            ['La Guerre des boutons', 90, '18/04/1962', 7, 0, 'comédie', ['Galabru Michel']],
            ['Jupiter : Le Destin de l\'univers', 127, '04/02/2015', 14, 12, 'science-fiction', ['Mila Kunis', 'Channing Tatum', 'Sean Bean']]
        ];

        foreach ($arr_genre as $nom) {
            $genre = new Genre();
            $genre->setNom($nom);
            $errors = $validator->validate($genre);
            if (count($errors) == 0) {
                $entityManager->persist($genre);
                $entityManager->flush();
            }
        }

        foreach ($arr_acteur as list($nomPrenom, $dateNaissance, $nationalite)) {
            $acteur = new Acteur();
            $acteur->setNomPrenom($nomPrenom)
                ->setDateNaissance(\DateTime::createFromFormat('d/m/Y', $dateNaissance))
                ->setNationalite($nationalite);
            $errors = $validator->validate($acteur);
            if (count($errors) == 0) {
                $entityManager->persist($acteur);
                $entityManager->flush();
            }
        }

        foreach ($arr_film as list($titre, $duree, $dateSortie, $note, $ageMinimal, $nomGenre, $nomActeurs)) {
            $film = new Film();
            $film->setTitre($titre)
                ->setDuree($duree)
                ->setDateSortie(\DateTime::createFromFormat('d/m/Y', $dateSortie))
                ->setNote($note)
                ->setAgeMinimal($ageMinimal);
            $genre = $this->getDoctrine()->getRepository(Genre::class)->findOneBy(['nom' => $nomGenre]);
            $film->setGenre($genre);
            foreach ($nomActeurs as $nomPrenom) {
                $acteur = $this->getDoctrine()->getRepository(Acteur::class)->findOneBy(['nomPrenom' => $nomPrenom]);
                $film->addActeur($acteur);
            }
            $errors = $validator->validate($film);
            if (count($errors) == 0) {
                $entityManager->persist($film);
                $entityManager->flush();
            }
        }


        return $this->redirectToRoute("accueil");
    }
}
