<?php

namespace App\DataFixtures;

use App\Entity\TypeMagasin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TypeMagasinFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager) 
    {/*
        $typeMagasins = ["Bricolage", "Sport", "Animaux", "Alimentaire", "Fleurs"];
        foreach($typeMagasins as $typeMagasin)
        {
            $typeMagasinFixture = new TypeMagasin();
            $typeMagasinFixture->setType($typeMagasin);
            $manager->persist($typeMagasinFixture);
        }
        $manager->flush();*/
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
