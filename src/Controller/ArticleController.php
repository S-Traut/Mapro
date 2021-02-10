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
     * @Route("/shop/articles/{id<\d+>}")
     */
    public function list(ArticleRepository $articleRepository, $id)
    {
        $articles = $articleRepository->findArticlesByMagasinId($id);
        return $this->render('article/articles.html.twig', [
            'articles' => $articles
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


    /**
     * @Route("/article/delete/{id<\d+>}")
     */
    public function delete(Article $article, EntityManagerInterface $em, Request $request)
    {
        if (!$article) {
            throw $this->createNotFoundException('Article Inexistant !');
        }
        $shopid = $article->getMagasin()->getId();
        $em->remove($article);
        $em->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            /*return $this->redirectToRoute('app_admin_annonce_index');*/
        }

        return $this->redirectToRoute('app_article_list', [
            'id' => $shopid
        ]);

    }

    /**
     * @Route("/article/{id}/edit")
     */
    public function edit(Article $article, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_article_list', ['id' => $article->getMagasin()->getId()]);         
        }
        
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

}
