<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request): Response
    { 
        $cookies = $request->cookies;
        return $this->render('home/home.html.twig', [
            'Longitude' => $cookies->get('userLongitude'),
            'Latitude' => $cookies->get('userLatitude')
        ]);
    }
}
