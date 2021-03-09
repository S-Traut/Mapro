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
        //User Admin        
        $utilisateurTest = new User();
        $utilisateurTest->setRoles(['ROLE_ADMIN']);
        $utilisateurTest->setNom("UtilisateurAdminNom");
        $utilisateurTest->setPrenom("UtilisateurAdminPrénom");
        $password = $this->encoder->encodePassword($utilisateurTest, 'admin');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("admin@admin.com");
        $manager->persist($utilisateurTest);

        //User Vendeur
        $utilisateurTest = new User();
        $utilisateurTest->setRoles(['ROLE_VENDEUR']);
        $utilisateurTest->setNom("TAUFF");
        $utilisateurTest->setPrenom("Kriss");
        $password = $this->encoder->encodePassword($utilisateurTest, 'Kriss');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("kriss@gmail.com");
        $manager->persist($utilisateurTest);

        $utilisateurTest = new User();
        $utilisateurTest->setRoles(['ROLE_VENDEUR']);
        $utilisateurTest->setNom("VILAR");
        $utilisateurTest->setPrenom("Maxime");
        $password = $this->encoder->encodePassword($utilisateurTest, 'Maxime');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("maxime.vilars@gmail.com");
        $manager->persist($utilisateurTest);

        // User Client
        $utilisateurTest = new User();
        $utilisateurTest->setRoles(['ROLE_CLIENT']);
        $utilisateurTest->setNom("KEVIN");
        $utilisateurTest->setPrenom("Jean");
        $password = $this->encoder->encodePassword($utilisateurTest, 'Jean');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("jean@gmail.com");
        $manager->persist($utilisateurTest);

        $utilisateurTest = new User();
        $utilisateurTest->setRoles(['ROLE_CLIENT']);
        $utilisateurTest->setNom("POTTER");
        $utilisateurTest->setPrenom("Harry");
        $password = $this->encoder->encodePassword($utilisateurTest, 'Harry');
        $utilisateurTest->setPassword($password);
        $utilisateurTest->setEmail("harry@gmail.com");
        $manager->persist($utilisateurTest);

        $manager->flush();
    }
}
