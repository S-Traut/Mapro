<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/administration")
*/
class AdministrationController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(MagasinRepository $magasinRepository): Response
    {
        $value = $magasinRepository->findBy(['etat' => 0]);
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
            'current_menu' => 'admin',
            'nbMagasinsAttentes' => $value
        ]);
    }

    /**
     * @Route("/waitlist")
     */
    public function magasinsEnAttentes(MagasinRepository $magasinRepository)
    {
        $value = $magasinRepository->findBy(['etat' => 0]);
        
        return $this->render('administration/listesAttentes.html.twig', [
            'magasins' => $value
        ]);
    }

    /**
     * @Route("/articles/liste")
     */
    public function listeArticles(ArticleRepository $articleRepository)
    {
        return $this->render('administration/listesArticles.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/magasins/liste")
     */
    public function listeMagasins(MagasinRepository $magasinRepository)
    {
        return $this->render('administration/listesMagasins.html.twig', [
            'magasins' => $magasinRepository->findAll()
        ]);
    }

    /**
     * @Route("/statistiques")
     */
    public function listeStatistiques()
    {
        return $this->render('administration/statistiques.html.twig', [
            
        ]);
    }

}

