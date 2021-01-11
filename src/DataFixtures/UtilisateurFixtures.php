<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $utilisateurTest = new User();
        $utilisateurTest->setRoles([0]);
        $utilisateurTest->setNom("UtilisateurTest");
        $utilisateurTest->setPrenom("UtilisateurTestPrénom");
        $password = $this->encoder->encodePassword($utilisateurTest, 'test');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("test@test.com");
        
        $manager->persist($utilisateurTest);
        $manager->flush();
    }
}
