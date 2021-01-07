<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        if(!$magasin && !$articles)
        {
            throw $this->createNotFoundException('Magasin Inexistant !');
        }
        return $this->render('magasin/show.html.twig', [
            'magasin' => $magasin,
            'articles' => $articles,
            'articlesPop' => $articlesPop
        ]); 
    }
}