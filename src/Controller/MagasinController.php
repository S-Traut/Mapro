<?php

namespace App\Controller;

use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    /**
     * @Route("/magasin/{id<\d+>}")
     */
    public function show(MagasinRepository $magasinRepository, $id)
    {   
        if(!$magasinRepository->find($id))
        {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }
        return $this->render('magasin/show.html.twig', [
            'magasin' => $magasinRepository->find($id)
        ]);
    }
}