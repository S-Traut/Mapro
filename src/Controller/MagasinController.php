<?php

namespace App\Controller;
use App\Entity\Localisation;
use App\Entity\Magasin;
use App\Entity\TypeMagasin;
use App\Entity\Utilisateur;
use App\Form\CreationMagasinType;
use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Location;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Forms;
use App\Form\EditionMagasinType;


class MagasinController extends AbstractController
{
    /**
     * @Route("/shop/{id<\d+>}")
     */
    public function show(MagasinRepository $magasinRepository, $id, ArticleRepository $articleRepository)
    {
        $magasin = $magasinRepository->find($id);
        $articles = $articleRepository->findArticlesByMagasinId($id);
        $articlesPop = $articleRepository->findArticlesPopulaires($id);
        if (!$magasin && !$articles) {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }
        return $this->render('magasin/show.html.twig', [
            'magasin' => $magasin,
            'articles' => $articles,
            'articlesPop' => $articlesPop
        ]);
    }
    /**
     * @Route("/shop/new", name="new_shop")
     * 
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("app_login");

        $magasin = new Magasin();
        $form = $this->createForm(CreationMagasinType::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //PLACEHOLDERS
            $magasin->setImage("https://www.retaildetail.be/sites/default/files/news/The%20Body%20Shop.jpg");
            $magasin->setEtat(0);
            $magasin->setIdUtilisateur($this->getUser());
            $em->persist($magasin);
            $em->flush();
            return $this->redirect("/shop/" . $magasin->getId());
        }
        return $this->render('magasin/new.html.twig', [
            'magasin' => $magasin,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/shop/{id<\d+>}/edit")
     */
    public function edit(MagasinRepository $magasinRepository, $id, Request $request, EntityManagerInterface $em){

        $magasin = $magasinRepository->find($id);

        if (!$magasin) {
            
            throw $this->createNotFoundException('Magasin Inexistant !');
        }

        //$magasin = new Magasin();


        $form = $this->createForm(CreationMagasinType::class, $magasin); 
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($magasin);
            $em->flush();
            return $this->redirect("/shop/" . $magasin->getId());
        }

        return $this->render('magasin/edit.html.twig', [
            'magasin' => $magasin,
            'form' => $form->createView()    
        ]);
    }
    /**
     * @Route("/shop/delete/{id<\d+>}")
     */
    public function delete(Magasin $shop, EntityManagerInterface $em, Request $request)
    {
        if (!$shop) {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }

        $em->remove($shop);
        $em->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            /*return $this->redirectToRoute('app_admin_annonce_index');*/
        }

        return $this->redirectToRoute('shops');

    }
}

