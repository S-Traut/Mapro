<?php

namespace App\Repository;

use App\Entity\StatistiqueMagasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatistiqueMagasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatistiqueMagasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatistiqueMagasin[]    findAll()
 * @method StatistiqueMagasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatistiqueMagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatistiqueMagasin::class);
    }

    // /**
    //  * @return StatistiqueMagasin[] Returns an array of StatistiqueMagasin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatistiqueMagasin
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
