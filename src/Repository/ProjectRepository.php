<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findBySearchQuery(?string $searchQuery): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if ($searchQuery) {
            $queryBuilder->andWhere('p.prname LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllSortedByName(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prname', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
