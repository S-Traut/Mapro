<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findArticlesByMagasinId($id)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.magasin = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findArticlesPopulaires($id)
    {
        return $this->createQueryBuilder('a')
            ->join('a.statistiqueArticle', 's')
            ->where('a.magasin = :val')
            ->setParameter('val', $id)
            ->orderBy('s.nbvue', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * Articles populaire à afficher sur la page home
     */
    public function findArticlesPopulairesHome($longitude, $latitude)
    {
        $sql = 'SQRT((' . $latitude . ' - m.latitude)*(' . $latitude . ' - m.latitude) + (' . $longitude . ' - m.longitude)*(' . $longitude . ' - m.longitude)) < 15.0';

        return $this->createQueryBuilder('a')
            ->join('a.statistiqueArticle', 's')
            ->join('a.magasin', 'm')
            ->where($sql)
            ->orderBy('s.nbvue', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->execute();
    }


    public function findByNameAndShop($value, $id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :val%')
            ->setParameter('val', $value)
            ->andWhere('a.magasin = :valId')
            ->setParameter('valId', $id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
