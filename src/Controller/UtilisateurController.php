<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Form\ChangePasswordType;
use App\Form\SetLocalisationType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/me", name="menu")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");
        
        $utilisateur = $this->getUser();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form_password = $this->createForm(ChangePasswordType::class);
        $form_localisation = $this->createForm(SetLocalisationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute("menu");
        }

        $form_password->handleRequest($request);
        if($form_password->isSubmitted() && $form_password->isValid())
        {
            $requestPassword = $request->get('change_password');
            $currentPassword = $passwordEncoder->isPasswordValid($utilisateur, $requestPassword['curPassword']);
            if($currentPassword && $requestPassword['newPassword'] == $requestPassword['newConfirm'])
            {
                $encodedPassword = $passwordEncoder->encodePassword($utilisateur, $requestPassword['newConfirm']);
                $utilisateur->setPassword($encodedPassword);
                $em->flush();
            }
        }

        $form_localisation->handleRequest($request);
        if($form_localisation->isSubmitted() && $form_localisation->isValid())
        {
            
            $localisation = new Localisation();
            $em->persist($localisation);
            $requestLocalisation = $request->get("set_localisation");
            $localisation->setAdresse($requestLocalisation['adresse']);
            $localisation->setLatitude($requestLocalisation['latitude']);
            $localisation->setLongitude($requestLocalisation['longitude']);
            $localisation->setUtilisateur($utilisateur);
            $em->flush();
        }

        return $this->render('utilisateur/index.html.twig', [
            'annonce' => $utilisateur,
            'form' => $form->createView(),
            'adresses' => $utilisateur->getLocalisation(),
            'password_form' => $form_password->createView(),
            'localisation_form' => $form_localisation->createView(),
        ]);
    }

    /**
     * @Route("/me/shops", name="shops")
     */
    public function shops(): Response
    {
        if($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");

        $utilisateur = $this->getUser();
        $magasins = $utilisateur->getMagasins();
        

        return $this->render('utilisateur/shops.html.twig', [
            'magasins' => $magasins
        ]);
        
    }
}
