<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Repository\ArticleRepository;
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
        UtilisateurController $utilisateurController
    ): Response {

        if ($this->getUser() != null) {

            //redirection vendeur vers ses magasins
            if ($this->getUser()->getRoles()[0] == "ROLE_VENDEUR" && $this->getUser()->getMagasins()[0] != null) {

                return $this->redirectToRoute('shops');
            }
        }

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

            return $this->render('home/home.html.twig', [
                'articles' => $articles,
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

        dump($longitude);
        dump($latitude);

        //pagination
        $magasins = $paginator->paginate($donnees, $request->query->getInt('page', 1), 10);

        return $this->render('home/categorieliste.html.twig', [
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
