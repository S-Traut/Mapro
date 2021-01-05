<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function show(Request $request): Response
    {
        if(isset($_COOKIE['userLongitude']) && isset($_COOKIE['userLatitude']))
        {
            $cookies = $request->cookies;
            return $this->render('home/home.html.twig', [
                'Longitude' => $cookies->get('userLongitude'),
                'Latitude' => $cookies->get('userLatitude')
            ]);
        }    
        return $this->render("home/prehome.html.twig", []);
    }
}