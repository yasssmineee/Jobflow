<?php

namespace App\Repository;

use App\Entity\Opportunite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opportunite>
 *
 * @method Opportunite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opportunite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opportunite[]    findAll()
 * @method Opportunite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportuniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunite::class);
    }

//    /**
//     * @return Opportunite[] Returns an array of Opportunite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Opportunite
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function search($q = null, $type = null, $sortBy = null)
{
    $queryBuilder = $this->createQueryBuilder('p');

    if ($q) {
        $queryBuilder->andWhere('p.nom LIKE :q')
            ->setParameter('q', '%' . $q . '%');
    }

    if ($type) {
        $queryBuilder->andWhere('p.type = :type')
            ->setParameter('type', $type);
    }

    if ($sortBy) {
        $queryBuilder->orderBy('p.' . $sortBy, 'ASC');
    }

    return $queryBuilder->getQuery()->getResult();
}

}