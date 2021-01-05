<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Form\CreationMagasinType;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

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
        return $this->render('magasin/magasin.html.twig', [
            'Magasin' => $magasinRepository->find($id)
        ]);
    }

    /**
     * @Route("/magasin/new")
     * 
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $magasin = new Magasin();

        $form = $this->createForm(CreationMagasinType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($magasin);
            $em->flush();
            return new Response('OK');
        }


        return $this->render('magasin/new.html.twig' , [
            'magasin' => $magasin,
            'form' => $form->createView()
        ]);
    }
}