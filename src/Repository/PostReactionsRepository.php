<?php

namespace App\Repository;

use App\Entity\PostReactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostReactions>
 *
 * @method PostReactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostReactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostReactions[]    findAll()
 * @method PostReactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostReactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostReactions::class);
    }

//    /**
//     * @return PostReactions[] Returns an array of PostReactions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostReactions
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function getOrderedByLikes()
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.likes', 'ASC')
        ->getQuery()
        ->getResult();
}

}
