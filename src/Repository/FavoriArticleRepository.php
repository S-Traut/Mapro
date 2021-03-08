<?php

namespace App\Repository;

use App\Entity\FavoriArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriArticle[]    findAll()
 * @method FavoriArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriArticle::class);
    }


    public function findByUserId($id){
     
        return $this->createQueryBuilder('f')
            ->where('f.idUtilisateur = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
      

    }




   public function findByArticleId($idArticle){

    return $this->createQueryBuilder('f')
    ->where('f.idArticle = :val')
    ->setParameter('val', $idArticle)
    ->getQuery()
    ->getResult()
;


   }


    // /**
    //  * @return FavoriArticle[] Returns an array of FavoriArticle objects
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

    
    public function findOneBySomeField($idu,$ida): ?FavoriArticle
    {
        $parameters = array('idu' => $idu,
                             'ida' => $ida);
        return $this->createQueryBuilder('f')
            ->Where('f.idUtilisateur = :idu')
            ->andWhere('f.idArticle = :ida')
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}