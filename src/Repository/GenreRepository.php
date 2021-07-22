<?php

namespace App\Repository;

use App\Entity\Acteur;
use App\Entity\Genre;
use App\Entity\GenreSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findAllVisible(GenreSearch $search)
    {
        $genres = $this->createQueryBuilder('g')
            ->getQuery()
            ->getResult();

        if ($search->getActeur()) {
            $genres = array_intersect($genres, $this->findTwoByActeur($search->getActeur()->getId()));
        }

        return $genres;
    }

    public function findTwoByActeur($acteurId)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.films', 'f')
            ->innerJoin('f.acteur', 'a')
            ->where('a.id = :acteurId')
            ->groupBy('g')
            ->having('COUNT(g.id) >= 2')
            ->setParameter('acteurId', $acteurId)
            ->getQuery()
            ->getResult();
    }

    public function findByActeur($acteurId)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.films', 'f')
            ->innerJoin('f.acteur', 'a')
            ->where('a.id = :acteurId')
            ->setParameter('acteurId', $acteurId)
            ->select('g.nom')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
}
