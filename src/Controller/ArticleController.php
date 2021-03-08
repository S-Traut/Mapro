<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Magasin;
use App\Form\ArticleType;
use App\Entity\StatistiqueArticle;
use App\Repository\ArticleRepository;
use App\Repository\FavoriArticleRepository;
use App\Repository\MagasinRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StatistiqueArticleRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Date;

class ArticleController extends AbstractController
{


    /**
     * @Route("/article/{id<\d+>}")
     */
    public function show(
        ArticleRepository $articleRepository,
        $id,
        StatistiqueArticleRepository $statistiqueArticleRepository,
        EntityManagerInterface $em,
        FavoriArticleRepository $favArtRepo
    ) {
        $article = $articleRepository->find($id);

        $utilisateur = $this->getUser();

        $favoris = [];
        if ($utilisateur) {
            $favoris = $favArtRepo->findOneBySomeField($utilisateur->getId(), $id);
        }

        if (!$article) {
            throw $this->createNotFoundException('Article Inexistant !');
        } else {
            $magasin = $article->getMagasin()->getNom();
            //$images = $article->getImage();
            // On vérifie que les stats de la page existe
            $statArticle = $statistiqueArticleRepository->findBy(['article' => $id]);
            // si la page n'existe pas on la créer et on ajoute +1
            $date = new DateTime();
            if (!$statArticle) {
                $statArticle = new StatistiqueArticle();
                $statArticle
                    ->setArticle($article)
                    ->setNbvue(1)
                    ->setDate($date);
                $em->persist($statArticle);
            } else {
                // si la page existe on modifie
                $statArticle[0]
                    ->setNbvue($statArticle[0]->getNbvue() + 1)
                    ->setDate($date);
                $em->persist($statArticle[0]);
            }
            $em->flush();
            return $this->render('article/show.html.twig', [
                'favoris' => $favoris,
                'article' => $article,
                'magasin' => $magasin,
                //'images' => $images
            ]);
        }
    }

    /**
     * @Route("/shop/{id<\d+>}/articles", name="app_article_list")
     */
    public function list(ArticleRepository $articleRepository, MagasinRepository $magasinRepository, $id)
    {
        $articles = $articleRepository->findArticlesByMagasinId($id);
        $magasin = $magasinRepository->find($id);
        dump($articles);
        if ($magasin) {
            if ($magasin->getIdUtilisateur() != $this->getUser() || $this->getUser() == null) {
                return $this->redirectToRoute('landing');
            }
            return $this->render('article/articles.html.twig', [
                'shopId' => $id,
                'articles' => $articles
            ]);
        } else {
            return $this->redirectToRoute('landing');
        }
    }


    /**
     * @Route("/shop/{id<\d+>}/newArticle")
     * 
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em, $id, MagasinRepository $magasinRepository)
    {
        $article = new Article();
        $magasin = $magasinRepository->find($id);

        if ($magasin->getIdUtilisateur() != $this->getUser() || $this->getUser() == null) {
            return $this->redirectToRoute('landing');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();

            $article->setEtat(1);
            $article->setMagasin($magasin);

            $statArticle = new StatistiqueArticle();
            $statArticle
                ->setArticle($article)
                ->setNbvue(0)
                ->setDate($date);

            $em->persist($statArticle);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute("app_article_list", ['id' => $article->getMagasin()->getId()]);
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/article/delete")
     */
    public function delete(EntityManagerInterface $em, ArticleRepository $articleRepository, Request $request)
    {
        $article = $articleRepository->find($request->get('id'));
        if (!$this->isGranted('ROLE_ADMIN')) {
            if ($article->getMagasin()->getIdUtilisateur() != $this->getUser() || $this->getUser() == null) {
                return $this->redirectToRoute('landing');
            }
        }

        if (!$article) {
            throw $this->createNotFoundException('Article Inexistant !');
        }
        $shopid = $article->getMagasin()->getId();
        $images = $article->getImage();
        foreach ($images as $image) {
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
