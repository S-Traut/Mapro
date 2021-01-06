<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Entity\Magasin;
use App\Entity\TypeMagasin;
use App\Form\CreationMagasinType;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Location;

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

        $form = $this->createForm(CreationMagasinType::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //PLACEHOLDERS
            $magasin->setImage("https://www.retaildetail.be/sites/default/files/news/The%20Body%20Shop.jpg");
            $magasin->setEtat(0);
            $magasin->setLatitude(0);
            $magasin->setLongitude(0);

            $em->persist($magasin);
            dump($magasin);
            $em->flush();
            return $this->redirectToRoute('/shop/'.$magasin->getId());
        }

        return $this->render('magasin/new.html.twig' , [
            'magasin' => $magasin,
            'form' => $form->createView()
        ]);
    }
}