<?php

namespace App\DataFixtures;

use App\Entity\TypeArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $typeArticle = new TypeArticle();

        $typeArticle->setType("testFixture");
    
        $manager->persist($typeArticle);
        $manager->flush();
    }
}
