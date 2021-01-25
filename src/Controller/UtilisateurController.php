<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/me", name="menu")
     */
    public function index(): Response
    {
        if($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");
        
        $utilisateur = $this->getUser();
        $form = $this->createForm(UserType::class, $utilisateur);

        return $this->render('utilisateur/index.html.twig', [
            'annonce' => $utilisateur,
            'form' => $form->createView() // la méthode createView est très important ! Si tu l'oublie, Twig ne pourra pas interpréter le formulaire
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
