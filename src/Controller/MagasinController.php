<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Entity\Magasin;
use App\Entity\StatistiqueMagasin;
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
use App\Repository\FavoriMagasinRepository;
use App\Repository\StatistiqueMagasinRepository;
use DateTime;

class MagasinController extends AbstractController
{
    /**
     * @Route("/shop/{id<\d+>}")
     */
    public function show(FavoriMagasinRepository $favMagRepo, MagasinRepository $magasinRepository, $id, ArticleRepository $articleRepository, StatistiqueMagasinRepository $statistiqueMagasinRepository, EntityManagerInterface $em)
    {

        $magasin = $magasinRepository->find($id);

        $utilisateur = $this->getUser();

        $favoris = [];

        if ($utilisateur) {
            $favoris = $favMagRepo->findOneBySomeField($utilisateur->getId(), $id);
        }

        if (!$magasin) {
            throw $this->createNotFoundException('Magasin Inexistant !');
        } else {
            if($magasin->getEtat() == 0) {
                return $this->redirectToRoute('Landing');
            }

            $articles = $articleRepository->findArticlesByMagasinId($id);
            $articlesPop = $articleRepository->findArticlesPopulaires($id);
            $statMag = $statistiqueMagasinRepository->findBy(['magasin' => $id]);

            $date = new DateTime();
            if (!$statMag) {
                $statMag = new StatistiqueMagasin();
                $statMag
                    ->setDate($date)
                    ->setNbvue(1)
                    ->setMagasin($magasin);
                $em->persist($statMag);
            } else {
                $statMag[0]
                    ->setNbvue($statMag[0]->getNbvue() + 1)
                    ->setDate($date);
                $em->persist($statMag[0]);
            }
            $em->flush();
            return $this->render('magasin/show.html.twig', [
                'favori' => $favoris,
                'magasin' => $magasin,
                'articles' => $articles,
                'articlesPop' => $articlesPop
            ]);
        }
    }
    /**
     * @Route("/shop/new", name="new_shop")
     * 
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        if ($this->isGranted('ROLE_VENDEUR') == false)
            return $this->redirectToRoute("app_login");

        $magasin = new Magasin();
        $form = $this->createForm(CreationMagasinType::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //PLACEHOLDERS
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
     * @Route("/shop/{id<\d+>}/edit", name="app_magasin_edit")
     */
    public function edit(MagasinRepository $magasinRepository, $id, Request $request, EntityManagerInterface $em)
    {

        $magasin = $magasinRepository->find($id);
        if ($this->getUser() != $magasin->getIdUtilisateur() || $this->getUser() == null) {
            return $this->redirectToRoute('landing');;
        }

        if (!$magasin) {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }

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
        if (!$this->isGranted('ROLE_ADMIN')) {
            if ($this->getUser() != $shop->getIdUtilisateur() && $shop->getUser == null) {
                return $this->redirectToRoute('landing');;
            }
        }

        if (!$shop) {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }

        $articles = $shop->getArticles();
        foreach ($articles as $article) {
            $em->remove($article);
        }

        $em->remove($shop);
        $em->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_administration_listemagasins');
        }

        return $this->redirectToRoute('shops');
    }
}
