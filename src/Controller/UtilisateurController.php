<?php

namespace App\Controller;

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
        return $this->render('utilisateur/index.html.twig', [
        ]);
    }
}
