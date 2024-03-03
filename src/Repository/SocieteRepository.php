<?php

namespace App\Repository;

use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Societe>
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
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
// In SocieteRepository.php

public function getStatistiques()
    {
        return $this->createQueryBuilder('e')
        ->select('e.nom as nom, e.secteur as secteur, count(e.id) as count')
        ->groupBy('e.nom, e.secteur')
        ->getQuery()
        ->getResult();
    }


   
}
