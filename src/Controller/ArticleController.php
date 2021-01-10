<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id<\d+>}")
     */
    public function show(ArticleRepository $articleRepository, $id)
    {
        $article = $articleRepository->findBy($id);
        if(!$article)
        {
            throw $this->createNotFoundException('Article Inexistant !');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}
