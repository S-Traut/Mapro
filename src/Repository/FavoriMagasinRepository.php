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

    public function findByUserId($id)
    {
        return $this->createQueryBuilder('f')
            ->where('f.idUtilisateur = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByMagId($idMag)
    {
        return $this->createQueryBuilder('f')
            ->where('f.idMagasin = :val')
            ->setParameter('val', $idMag)
            ->getQuery()
            ->getResult();
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


    public function findOneBySomeField($idU, $idM): ?FavoriMagasin
    {
        $parameters = array(
            'idu' => $idU,
            'idm' => $idM
        );

        return $this->createQueryBuilder('f')
            ->where('f.idUtilisateur = :idu')
            ->andWhere('f.idMagasin = :idm')
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
