<?php

namespace App\DataFixtures;

use App\Entity\TypeMagasin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TypeMagasinFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager) 
    {
        $typeArticles = ["Bricolage", "Sport", "Animaux", "Alimentaire", "Fleurs"];
        foreach($typeArticles as $typeArticle)
        {
            $typeMagasinFixture = new TypeMagasin();
            $typeMagasinFixture->setType($typeArticle);
            $manager->persist($typeMagasinFixture);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
