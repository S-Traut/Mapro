<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Magasin;
use App\Form\ArticleType;
use App\Entity\StatistiqueArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StatistiqueArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id<\d+>}")
     */
    public function show(ArticleRepository $articleRepository, $id, StatistiqueArticleRepository $statistiqueArticleRepository, EntityManagerInterface $em)
    {
        
        $article = $articleRepository->find($id);
        if(!$article)
        {
            throw $this->createNotFoundException('Article Inexistant !');
        }else{
            $magasin = $article->getMagasin()->getNom();
            $images = $article->getImage();
            // On vérifie que les stats de la page existe
            $statArticle = $statistiqueArticleRepository->find($id);
            // si la page n'existe pas on la créer et on ajoute +1
            $date = new DateTime();
            if(!$statArticle){
                $statArticle = new StatistiqueArticle();
                $statArticle
                    ->setArticle($article)
                    ->setNbvue(1)
                    ->setDate($date);
                $em->persist($statArticle);
            } else{
                // si la page existe on modifie
                $nbVue = $statArticle->getNbvue();
                $statArticle
                    ->setNbvue($nbVue + 1)
                    ->setDate($date);
                $em->persist($statArticle);
            }
            $em->flush();
            return $this->render('article/show.html.twig', [
                'article' => $article,
                'magasin' => $magasin,
                'images' => $images
            ]);
        }    
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
        $images = $article->getImage();
        foreach($images as $image){
            $em->remove($image);
        }
        $em->remove($article);
        $em->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_administration_listearticles');
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
