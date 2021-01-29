<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/me", name="menu")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");
        
        $utilisateur = $this->getUser();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute("menu");
        }

        dump($utilisateur->getLocalisation()[0]);

        return $this->render('utilisateur/index.html.twig', [
            'annonce' => $utilisateur,
            'form' => $form->createView(), // la méthode createView est très important ! Si tu l'oublie, Twig ne pourra pas interpréter le formulaire
            'adresses' => $utilisateur->getLocalisation()
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
