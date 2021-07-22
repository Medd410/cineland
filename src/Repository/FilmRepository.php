<?php

namespace App\Repository;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\FilmSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findAllVisible(FilmSearch $search)
    {
        $films = $this->createQueryBuilder('f')
            ->getQuery()
            ->getResult();

        if ($search->getTitre()) {
            $films = array_intersect($films, $this->findByTitre($search->getTitre()));
        }

        if ($search->getAnneeMin()) {
            $films = array_intersect($films, $this->findByAnneeMin($search->getAnneeMin()));
        }

        if ($search->getAnneeMax()) {
            $films = array_intersect($films, $this->findByAnneeMax($search->getAnneeMax()));
        }

        if($search->getDateMax()) {
            $films = array_intersect($films, $this->findByDateMax($search->getDateMax()->format('Y-m-d')));
        }

        if ($search->getActeurUn() && $search->getActeurDeux()) {
            if ($search->getActeurUn()->getId() != $search->getActeurDeux()->getId()) {
                $films = array_intersect($films, $search->getActeurUn()->getFilms()->toArray(), $search->getActeurDeux()->getFilms()->toArray());
            }
        }

        return $films;
    }

    public function findByTitre($titre) {
        return $this->createQueryBuilder('f')
            ->where('f.titre LIKE :titre')
            ->setParameter('titre', "%$titre%")
            ->getQuery()
            ->getResult();
    }

    public function findByAnneeMin($anneeMin) {
        return $this->createQueryBuilder('f')
            ->where('f.dateSortie >= :anneeMin')
            ->setParameter('anneeMin', "$anneeMin-01-01")
            ->getQuery()
            ->getResult();
    }

    public function findByAnneeMax($anneeMax) {
        return $this->createQueryBuilder('f')
            ->where('f.dateSortie <= :anneeMax')
            ->setParameter('anneeMax', "$anneeMax-12-31")
            ->getQuery()
            ->getResult();
    }

    public function findByDateMax($dateMax) {
        return $this->createQueryBuilder('f')
            ->where('f.dateSortie <= :dateMax')
            ->setParameter('dateMax', $dateMax)
            ->getQuery()
            ->getResult();
    }

    public function findByActeur($acteurId)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.acteur', 'a')
            ->where('a.id = :acteurId')
            ->setParameter('acteurId', $acteurId)
            ->orderBy('f.dateSortie')
            ->select('f.titre, f.dateSortie')
            ->getQuery()
            ->getResult();
    }
}
