<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Images des Articles populaire à afficher sur la page home
     */
    public function ImagesArticlesPopulaire($articles)
    {
        //tableau des id d'articles
        $tableau = [
            $articles[0]->getId(),
            $articles[1]->getId(),
            $articles[2]->getId(),
            $articles[3]->getId(),
            $articles[4]->getId(),
            $articles[5]->getId(),
            $articles[6]->getId(),
            $articles[7]->getId(),
            $articles[8]->getId(),
            $articles[9]->getId(),
            $articles[10]->getId(),
            $articles[11]->getId()
        ];
        //join tous les articles concernés
        $ids = join("','", $tableau);
        //toutes les tables image concernées
        $sql = "image.article in ('$ids')";

        return $this->createQueryBuilder('image')
            ->where($sql)
            ->getQuery()
            ->execute();
    }

    /*public function ImagesArticle($longitude, $latitude)
    {
        $sql = 'SQRT((' . $latitude . ' - Magasin.latitude)*(' . $latitude . ' - Magasin.latitude) + (' . $longitude . ' - Magasin.longitude)*(' . $longitude . ' - Magasin.longitude)) < 15.0';

        return $this->createQueryBuilder('image')
            ->join('image.Article', 'a')
            ->join('Article.StatistiqueArticle', 's')
            ->where($sql)
            ->andWhere('Magasin.typeMagasin = :type')
            ->setParameter('type', $categorie)
            ->getQuery()
            ->execute();
    }*/

    // /**
    //  * @return Image[] Returns an array of Image objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
