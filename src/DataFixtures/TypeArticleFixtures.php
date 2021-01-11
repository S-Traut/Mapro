<?php

namespace App\DataFixtures;

use App\Entity\TypeArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TypeArticleFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {/*
        $typeArticles = ["Nourriture", "Vêtement", "Jouets"];
        foreach($typeArticles as $typeArticle)
        {
            $typeArticleFixture = new TypeArticle();
            $typeArticleFixture->setType($typeArticle);
            $manager->persist($typeArticleFixture);
        }
        $manager->flush();*/
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
