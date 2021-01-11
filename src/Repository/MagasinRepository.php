<?php

namespace App\Repository;

use App\Entity\Magasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Magasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasin[]    findAll()
 * @method Magasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Magasin::class);
    }

    /**
     * Recherche les magasins en fonction du nom et des coordonnées géo
     */
    public function search($nom, $longitude, $latitude)
    {

        $sql = 'SQRT((' . $latitude . ' - Magasin.latitude)*(' . $latitude . ' - Magasin.latitude) + (' . $longitude . ' - Magasin.longitude)*(' . $longitude . ' - Magasin.longitude)) < 0.01';

        return $this->createQueryBuilder('Magasin')
            ->where($sql)
            ->andWhere('Magasin.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->execute();
    }

    public function searchCategorie($categorie, $longitude, $latitude)
    {
        $sql = 'SQRT((' . $latitude . ' - Magasin.latitude)*(' . $latitude . ' - Magasin.latitude) + (' . $longitude . ' - Magasin.longitude)*(' . $longitude . ' - Magasin.longitude)) < 0.01';

        return $this->createQueryBuilder('Magasin')
            ->where($sql)
            ->andWhere('Magasin.typeMagasin = :type')
            ->setParameter('type', $categorie)
            ->getQuery()
            ->execute();
    }
}
