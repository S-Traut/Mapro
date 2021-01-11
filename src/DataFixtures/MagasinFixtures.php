<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Magasin;
use Faker\Factory as Faker;
use App\DataFixtures\TypeMagasinFixtures;
use App\Entity\TypeMagasin;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MagasinFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');
        for ($i=0; $i < 10; $i++) { 
            $typeMagasin = new TypeMagasin();
            $typeMagasin
                ->setType($faker->text(10));
            $manager->persist($typeMagasin);  
            $magasin = new Magasin();
            $magasin
                ->setTypeMagasin($typeMagasin)
                ->setEmail($faker->email)
                ->setEtat(1)
                ->setImage($faker->imageUrl())
                ->setLatitude($faker->latitude)
                ->setLongitude($faker->longitude)
                ->setNom($faker->company)
                ->setSiren($faker->siren)
                ->setTel($faker->phoneNumber)
                ->setAdresse($faker->address)
                ->setDescription($faker->text(200));
                $manager->persist($magasin);
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
