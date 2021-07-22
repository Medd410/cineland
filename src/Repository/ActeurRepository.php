<?php

namespace App\Repository;

use App\Entity\Acteur;
use App\Entity\ActeurSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acteur[]    findAll()
 * @method Acteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acteur::class);
    }

    public function findAllVisible(ActeurSearch $search)
    {
        $acteurs = $this->createQueryBuilder('a')
            ->getQuery()
            ->getResult();

        if ($search->getFilm()) {
            $acteurs = array_intersect($acteurs, $this->findByFilm($search->getFilm()->getId()));
        }

        if ($search->getFilmMin()) {
            $acteurs = array_intersect($acteurs, $this->findByFilmMin($search->getFilmMin()));
        }

        return $acteurs;
    }

    public function findByFilm($filmId) {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.films', 'f')
            ->where('f.id = :filmId')
            ->setParameter('filmId', $filmId)
            ->getQuery()
            ->getResult();
    }

    public function findByFilmMin($filmMin) {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.films', 'f')
            ->groupBy('a')
            ->having('COUNT(f.id) >= :filmMin')
            ->setParameter('filmMin', $filmMin)
            ->getQuery()
            ->getResult();
    }
}
