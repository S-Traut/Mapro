<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Magasin;
use Faker\Factory as Faker;
use App\DataFixtures\TypeMagasinFixtures;
use App\Entity\Article;
use App\Entity\StatistiqueArticle;
use App\Entity\TypeArticle;
use App\Entity\TypeMagasin;
use App\Entity\Image;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MagasinFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');
        $typesMagasin = [];
        $nomTypes = ["Alimentaire", "Vêtements", "Jeux", "Librairie", "Bien-être", "Electronique", "Santé"];
        foreach ($nomTypes as $type) {
            //Création des types magasins
            $typeMagasin = new TypeMagasin();
            $typeMagasin->setType($type);
            $manager->persist($typeMagasin);
            array_push($typesMagasin, $typeMagasin);
        }

        for ($i = 0; $i < 50; $i++) {

            $latitude = $faker->randomFloat(5, 48.45, 48.55);
            $longitude = $faker->randomFloat(5, 7.65, 7.75);

            //Création des magasins
            $magasin = new Magasin();
            $magasin
                ->setTypeMagasin($typesMagasin[random_int(0, 5)])
                ->setEmail($faker->email)
                ->setEtat(1)
                ->setLatitude($latitude)
                ->setLongitude($longitude)
                ->setNom($faker->company)
                ->setSiren($faker->siren)
                ->setTel($faker->phoneNumber)
                ->setAdresse($faker->address)
                ->setDescription($faker->text(200))
                ->setImageName("fixtures/".rand(0, 10).".jpg");
            $manager->persist($magasin);

            //Création des types articles
            $typeArticle = new TypeArticle();
            $typeArticle
                ->setType($faker->text(10));
            $manager->persist($typeArticle);

            for($j = 0; $j < random_int(0, 10); $j++) {
                //Création des articles
                $article = new Article();
                $article
                    ->setNom($faker->text(10))
                    ->setPrix($faker->randomFloat(2, 0, 100))
                    ->setDescription($faker->text(200))
                    ->setEtat(1)
                    ->setMagasin($magasin)
                    ->setType($typeArticle)
                    ->setImageName("fixtures/".rand(0, 13).".jpg");
                $manager->persist($article);   

                //Création d'une image d'article             
                $image = new Image();
                $image
                    ->setArticle($article)
                    ->setImage($faker->imageUrl());
                $manager->persist($image);
                

                //Création des statistiques articles
                $statsArticle = new StatistiqueArticle();
                $statsArticle
                    ->setArticle($article)
                    ->setDate($faker->dateTimeBetween('+0 days', '+1 years'))
                    ->setNbvue($faker->randomDigit);
                $manager->persist($statsArticle);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TypeMagasinFixtures::class,
        );
    }
}
