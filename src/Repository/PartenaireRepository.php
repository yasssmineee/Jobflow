<?php

namespace App\Repository;

use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PartenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }

    public function findBySearchTerm($searchTerm): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

public function getStatistiques()
    {
        return $this->createQueryBuilder('e')
        ->select('e.nom as nom, e.duree as duree, count(e.id) as count')
        ->groupBy('e.nom, e.duree')
        ->getQuery()
        ->getResult();
    }

}
