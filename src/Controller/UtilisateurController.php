<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Form\ChangePasswordType;
use App\Form\SetLocalisationType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\FavoriArticleRepository;
use App\Repository\FavoriMagasinRepository;
use App\Repository\LocalisationRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/me", name="menu")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");

        $utilisateur = $this->getUser();
        //dump($utilisateur);
        $form = $this->createForm(UserType::class, $utilisateur);
        $form_password = $this->createForm(ChangePasswordType::class);
        $form_localisation = $this->createForm(SetLocalisationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute("menu");
        }

        $form_password->handleRequest($request);
        if ($form_password->isSubmitted() && $form_password->isValid()) {
            $requestPassword = $request->get('change_password');
            $currentPassword = $passwordEncoder->isPasswordValid($utilisateur, $requestPassword['curPassword']);
            if ($currentPassword && $requestPassword['newPassword'] == $requestPassword['newConfirm']) {
                $encodedPassword = $passwordEncoder->encodePassword($utilisateur, $requestPassword['newConfirm']);
                $utilisateur->setPassword($encodedPassword);
                $em->flush();
            }
        }

        $form_localisation->handleRequest($request);
        if ($form_localisation->isSubmitted() && $form_localisation->isValid()) {

            $localisation = new Localisation();
            $em->persist($localisation);
            $requestLocalisation = $request->get("set_localisation");
            $localisation->setAdresse($requestLocalisation['adresse']);
            $localisation->setLatitude($requestLocalisation['latitude']);
            $localisation->setLongitude($requestLocalisation['longitude']);
            $localisation->setUtilisateur($utilisateur);
            $em->flush();
        }

        return $this->render('utilisateur/index.html.twig', [
            'annonce' => $utilisateur,
            'form' => $form->createView(),
            'adresses' => $utilisateur->getLocalisation(),
            'password_form' => $form_password->createView(),
            'localisation_form' => $form_localisation->createView(),
            'current_menu' => 'menu',
        ]);
    }

    /**
     * @Route("/me/shops", name="shops")
     */
    public function shops(): Response
    {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");

        $utilisateur = $this->getUser();
        $magasins = $utilisateur->getMagasins();

        return $this->render('utilisateur/shops.html.twig', [
            'magasins' => $magasins,
            'current_menu' => 'shops'
        ]);
    }

    /**
     * @Route("me/favoris", name="favoris")
     */
    public function favoris(
        FavoriMagasinRepository $favMagRepo,
        MagasinRepository $magasinRepo,
        ArticleRepository $articleRepo,
        Request $request,
        FavoriArticleRepository $favArtRepo
    ) {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirectToRoute("landing");

        //récupérer les coordonnées géo de l'utilisateur
        $cookies = $request->cookies;
        $longitude = $cookies->get('userLongitude');
        $latitude = $cookies->get('userLatitude');

        $utilisateur = $this->getUser();

        $magasins = $magasinRepo->findAll();
        $articles = $articleRepo->findAll();

        $listFavMag = array();
        $listFavArt = array();


        if ($utilisateur) {
            $favorisArt = $favArtRepo->findByUserId($utilisateur->getId());
            $favorisMag = $favMagRepo->findByUserId($utilisateur->getId());

            foreach ($magasins as $magasin) {
                foreach ($favorisMag as $mag) {
                    if ($mag->getIdMagasin() == $magasin->getId()) {
                        array_push($listFavMag, $magasin);
                        unset($favorisMag[array_search($mag, $favorisMag)]);
                        break 1;
                    }
                }
            }

            foreach ($articles as $article) {
                foreach ($favorisArt as $art) {
                    if ($art->getIdArticle() == $article->getId()) {
                        array_push($listFavArt, $article);
                        unset($favorisArt[array_search($art, $favorisArt)]);
                        break 1;
                    }
                }
            }
        }

        return $this->render("utilisateur/favoris.html.twig", [
            'favorisMagasins' => $listFavMag,
            'favorisArticles' => $listFavArt,
            'current_menu' => 'favoris'
        ]);
    }

    /**
     *
     * @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="deleteLocalisation")
     * @return RedirectResponse
     *
     */
    public function deleteLocalisation($id, LocalisationRepository $localisationRepository, EntityManagerInterface $em)
    {
        $utilisateur = $this->getUser();
        $localisation = $localisationRepository->find($id);
        $utilisateur->removeLocalisation($localisation);
        $em->remove($localisation);
        $em->flush();
        return $this->redirectToRoute("menu");
    }

    //Récupère la position de l'utilisateur, si elle n'existe pas tente de récupérer la position via les cookies.
    public function getCurrentLocalisation(Request $request): LocalisationVector
    {
        if ($this->getUser() != null && $this->getUser()->getLocalisation()[0] != null) {
            $userLocalisation = $this->getUser()->getLocalisation()[0];
            return new LocalisationVector($userLocalisation->getLatitude(), $userLocalisation->getLongitude());
        } else {
            $cookies = $request->cookies;
            $longitude = $cookies->get('userLongitude');
            $latitude = $cookies->get('userLatitude');
            return new LocalisationVector($latitude, $longitude);
        }
    }
}

class LocalisationVector
{
    public $latitude;
    public $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
