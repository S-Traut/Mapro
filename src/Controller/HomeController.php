<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
            $searchForm = $this->createForm(SearchType::class);
            $searchForm->handleRequest($request);

            //récup des articles populaire
            $articles = $articleRepo->findArticlesPopulairesHome($longitude, $latitude);

            //si une recherche a été soumise
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {

                return $this->search(
                    $request,
                    $magasinRepo,
                    $paginator,
                    $searchForm,
                    $articles,
                    $longitude,
                    $latitude
                );
            }

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
        $id,
        ArticleRepository $articleRepo
    ) {
        //récupérer les coordonnées géo de l'utilisateur
        $cookies = $request->cookies;
        $longitude = $cookies->get('userLongitude');
        $latitude = $cookies->get('userLatitude');

        //creation de la searchForm
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        //résultat de la recherche des magasins
        $donnees = $magasinRepo->searchCategorie($id, $longitude, $latitude);

        //si une recherche a été soumise
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //récup des articles populaire
            $articles = $articleRepo->findArticlesPopulairesHome($longitude, $latitude);

            return $this->search(
                $request,
                $magasinRepo,
                $paginator,
                $searchForm,
                $articles,
                $longitude,
                $latitude
            );
        }

        //pagination
        $magasins = $paginator->paginate($donnees, $request->query->getInt('page', 1), 4);

        dump($magasins[1]->getArticles()[0]);

        return $this->render('home/categorieliste.html.twig', [
            'magasins' => $magasins,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * recherche de magasins
     */
    public function search(
        Request $request,
        MagasinRepository $magasinRepo,
        PaginatorInterface $paginator,
        FormInterface $searchForm,
        $articles,
        $longitude,
        $latitude
    ) {

        $nom = $searchForm->getData();

        //résultat de la recherche des magasins
        $donnees = $magasinRepo->search($nom, $longitude, $latitude);

        if ($donnees == null) {
            $this->addFlash('erreur', 'Aucun magasin trouvé');
        }

        //pagination
        $magasins = $paginator->paginate($donnees, $request->query->getInt('page', 1), 4);

        return $this->render('home/resultathome.html.twig', [
            'magasins' => $magasins,
            'articles' => $articles,
            'searchForm' => $searchForm->createView()
        ]);
    }
}
