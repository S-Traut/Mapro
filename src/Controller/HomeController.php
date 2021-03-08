<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Repository\ArticleRepository;
use App\Repository\FavoriArticleRepository;
use App\Repository\FavoriMagasinRepository;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function show(
        Request $request,
        MagasinRepository $magasinRepo,
        PaginatorInterface $paginator,
        ArticleRepository $articleRepo,
        FavoriArticleRepository $favoriArticleRepo,
        UtilisateurController $utilisateurController
    ): Response {
        if (isset($_COOKIE['userLongitude']) && isset($_COOKIE['userLatitude'])) {
            //récupérer les coordonnées géo de l'utilisateur
            $cookies = $request->cookies;
            $longitude = $cookies->get('userLongitude');
            $latitude = $cookies->get('userLatitude');

            //creation de la searchForm
            $searchForm = $this->createForm(RechercheType::class, null, ['action' => $this->generateUrl('recherche')]);
            $searchForm->handleRequest($request);

            //récup des articles populaire
            $articles = $articleRepo->findArticlesPopulairesHome($longitude, $latitude);


            $utilisateur = $this->getUser();

            $listFavArticle = array();

            if ($utilisateur) {
                $favArticles = $favoriArticleRepo->findByUserId($utilisateur->getId());

                foreach ($articles as $article) {
                    foreach ($favArticles as $favArticle) {
                        if ($favArticle->getIdArticle() == $article->getId()) {
                            array_push($listFavArticle, $article);
                            unset($favArticles[array_search($favArticle, $favArticles)]);
                            unset($articles[array_search($article, $articles)]);
                            break 1;
                        }
                    }
                }
            }

            dump($listFavArticle);
            return $this->render('home/home.html.twig', [
                'articles' => $articles,
                'favorisArticles' => $listFavArticle,
                'searchForm' => $searchForm->createView()

            ]);
        }

        return $this->render("home/prehome.html.twig", []);
    }

    /**
     * Liste les magasins par categories
     * @Route("/categorie/{id<\d+>}")
     */
    public function listeMagasin(
        Request $request,
        MagasinRepository $magasinRepo,
        PaginatorInterface $paginator,
        FavoriMagasinRepository $favoriRepo,
        $id
    ) {
        //récupérer les coordonnées géo de l'utilisateur
        $cookies = $request->cookies;
        $longitude = $cookies->get('userLongitude');
        $latitude = $cookies->get('userLatitude');

        //creation de la searchForm
        $searchForm = $this->createForm(RechercheType::class, null, ['action' => $this->generateUrl('recherche')]);
        $searchForm->handleRequest($request);

        //résultat de la recherche des magasins
        $donnees = $magasinRepo->searchCategorie($id, $longitude, $latitude);

        //Récup les favoris de l'utilisateur
        $utilisateur = $this->getUser();

        $listFav = array();

        if ($utilisateur) {
            $favoris = $favoriRepo->findByUserId($utilisateur->getId());

            foreach ($donnees as $donnee) {
                foreach ($favoris as $favori) {
                    if ($favori->getIdMagasin() == $donnee->getId()) {
                        array_push($listFav, $donnee);
                        unset($favoris[array_search($favori, $favoris)]);
                        unset($donnees[array_search($donnee, $donnees)]);
                        break 1;
                    }
                }
            }
        }

        //pagination
        $magasins = $paginator->paginate($donnees, $request->query->getInt('page', 1), 10);

        return $this->render('home/categorieliste.html.twig', [
            'donnees' => $donnees,
            'favoris' => $listFav,
            'magasins' => $magasins,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/recherche", name="recherche")
     *  
     */
    public function recherche(
        Request $request,
        MagasinRepository $magasinRepo,
        PaginatorInterface $paginator,
        ArticleRepository $articleRepo
    ) {

        $donnees = null;

        if (isset($_COOKIE['userLongitude']) && isset($_COOKIE['userLatitude'])) {
            //récupérer les coordonnées géo de l'utilisateur
            $cookies = $request->cookies;
            $longitude = $cookies->get('userLongitude');
            $latitude = $cookies->get('userLatitude');

            //creation de la searchForm
            $searchForm = $this->createForm(RechercheType::class, null, ['action' => $this->generateUrl('recherche')]);
            $searchForm->handleRequest($request);

            //récup des articles populaire
            $articles = $articleRepo->findArticlesPopulairesHome($longitude, $latitude);

            //si une recherche a été soumise
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            }

            if ($searchForm->getData()['choix'] == 1) {
                //recherche magasins

                $donnees = $this->rechercheMagasin(
                    $magasinRepo,
                    $searchForm,
                    $longitude,
                    $latitude
                );
            } else {

                //recherche articles
                $donnees = $this->rechercheArticle(
                    $articleRepo,
                    $searchForm,
                    $longitude,
                    $latitude
                );
            }
        }


        $resultat = $paginator->paginate($donnees, $request->query->getInt('page', 1), 10);

        return $this->render('home/resultathome.html.twig', [
            'resultat' => $resultat,
            'articles' => $articles,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * recherche de magasins
     */
    public function rechercheMagasin(
        MagasinRepository $magasinRepo,
        FormInterface $searchForm,
        $longitude,
        $latitude
    ) {

        $nom = $searchForm->getData()['mot_cle'];

        //résultat de la recherche des magasins
        $donnees = $magasinRepo->search($nom, $longitude, $latitude);

        return $donnees;
    }

    /**
     * recherche d'articles
     */
    public function rechercheArticle(
        ArticleRepository $articleRepo,
        FormInterface $searchForm,
        $longitude,
        $latitude
    ) {
        $nom = $searchForm->getData()['mot_cle'];

        $donnees = $articleRepo->search($nom, $longitude, $latitude);

        return $donnees;
    }
}
