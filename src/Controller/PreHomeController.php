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
        return $this->render("pages/prehome.html.twig", [
            
        ]); 
    }
}