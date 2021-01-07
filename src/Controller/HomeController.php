<?php

namespace App\Controller;

use App\Repository\MagasinRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function show(Request $request): Response
    {
        if (isset($_COOKIE['userLongitude']) && isset($_COOKIE['userLatitude'])) {
            $cookies = $request->cookies;
            return $this->render('home/home.html.twig', [
                'Longitude' => $cookies->get('userLongitude'),
                'Latitude' => $cookies->get('userLatitude')
            ]);
        }
        return $this->render("home/prehome.html.twig", []);
    }

    /**
     * @Route("/recherche", name="search")
     */
    public function recherche(Request $request, MagasinRepository $magasinRepo, PaginatorInterface $paginator)
    {

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);


        $donnees = $magasinRepo->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $nom = $searchForm->getData();

            $donnees = $magasinRepo->search($nom);


            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun magasin trouvé');
            }
        }

        // Paginate the results of the query
        $magasins = $paginator->paginate(
            // Doctrine Query, not results
            $donnees,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('recherche.html.twig', [
            'magasins' => $magasins,
            'searchForm' => $searchForm->createView()
        ]);
    }
}
