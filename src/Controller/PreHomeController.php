<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreHomeController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function index(): Response
    {
        if(isset($_COOKIE['userLongitude']) && isset($_COOKIE['userLatitude']))
            return $this->redirectToRoute('home');


        return $this->render("home/prehome.html.twig", [
            
        ]);
    }
}