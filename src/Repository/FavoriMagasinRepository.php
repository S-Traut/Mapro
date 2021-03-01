<?php

namespace App\Repository;

use App\Entity\FavoriMagasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriMagasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriMagasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriMagasin[]    findAll()
 * @method FavoriMagasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriMagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriMagasin::class);
    }

    // /**
    //  * @return FavoriMagasin[] Returns an array of FavoriMagasin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FavoriMagasin
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
