<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Magasin;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id<\d+>}")
     */
    public function show(ArticleRepository $articleRepository, $id)
    {
        
        $article = $articleRepository->find($id);
        $magasin = $article->getMagasin()->getNom();
        $images = $article->getImage();
        if(!$article)
        {
            throw $this->createNotFoundException('Article Inexistant !');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'magasin' => $magasin,
            'images' => $images
        ]);
    }

    /**
     * @Route("/article/new")
     * 
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $article = new Article();
        
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        dump($this->getUser());
        if($form->isSubmitted() && $form->isValid()){
            $article->setEtat(1);
            $article->setMagasin($this->getUser()->getMagasins()[0]);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('landing');
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
}
