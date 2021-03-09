<?php

namespace App\Controller;

use Amp\Http\Status;
use App\Entity\FavoriArticle;
use App\Entity\FavoriMagasin;
use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FavoriArticleRepository;
use App\Repository\FavoriMagasinRepository;
use Symfony\Component\Serializer\Serializer;
use Amp\Http\Client\Request as ClientRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class APIController extends AbstractController
{
    /**
     * @Route("/api/get/searchAround", name="API_GET_SearchAround")
     */
    public function searchAround(MagasinRepository $magasinRepository, UtilisateurController $utilisateurController, Request $request): Response
    {
        $radius = $request->get('radius') * 0.00001;
        $localisation = new LocalisationVector($request->get('latitude'), $request->get('longitude'));
        $magasins = $magasinRepository->searchAround($localisation->longitude, $localisation->latitude, $radius);

        return $this->json($magasins, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['articles', 'typeMagasin', 'statistiqueMagasin', 'etat'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    /**
     * @Route("/api/get/userPosition", name="API_GET_UserPosition")
     */
    public function getUserPosition(UtilisateurController $utilisateurController, Request $request): Response
    {
        $localisation = $utilisateurController->getCurrentLocalisation($request);
        return $this->json($localisation, Response::HTTP_OK);
    }

    /**
     * @Route("/api/get/searchData", name="API_GET_SearchData")
     */
    public function searchData(MagasinRepository $magasinRepository, UtilisateurController $utilisateurController, Request $request): Response
    {
        $radius = $request->get('radius') * 0.00001;
        $localisation = new LocalisationVector($request->get('latitude'), $request->get('longitude'));
        $magasins = $magasinRepository->searchAround($localisation->longitude, $localisation->latitude, $radius);

        return $this->json($magasins, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['typeMagasin', 'statistiqueMagasin', 'etat'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    /**
     * @Route("/api/get/favorimag", name="APÏ_GET_FavoriMag")
     */
    /*public function favoriMag(FavoriMagasinRepository $favoriMagRepo)
    {

        $utilisateur = $this->getUser();

        $favori = $favoriMagRepo->findByUserId($utilisateur->getId());

        return $this->json($favori, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['localisation', 'magasins', 'typeMagasin', 'statistiqueMagasin', 'etat'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }*/

    /**
     * @Route("/api/set/favorimag", name="API_SET_FavoriMag")
     */
    public function setFavoriMag(Request $request)
    {
        $favori = new FavoriMagasin();
        $utilisateur = $this->getUser();

        $favori->setIdUtilisateur($utilisateur);
        $favori->setIdMagasin(intval($request->request->get('mag_id')));

        $em = $this->getDoctrine()->getManager();

        $em->persist($favori);
        $em->flush();

        return new JsonResponse(
            array(
                'status' => 'OK'
            ),
            200
        );
    }

    /**
     * @Route("/api/delete/favorimag", name="API_DELETE_FavoriMag")
     */
    public function deleteFavoriMag(Request $request, FavoriMagasinRepository $favMagRepo)
    {
        $utilisateur = $this->getUser();

        $favori = $favMagRepo->findOneBySomeField($utilisateur->getId(), $request->request->get('mag_id'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($favori);
        $em->flush();

        return new JsonResponse(
            array(
                'status' => 'OK'
            ),
            200
        );
    }

    /**
     * @Route("/api/set/favoriarticle", name="api_set_favoriarticle")
     */
    public function setFavArticle(Request $request)
    {

        $favori = new FavoriArticle();

        $utilisateur = $this->getUser();

        $favori->setIdUtilisateur($utilisateur);

        $favori->setIdArticle(intval($request->request->get('art_id')));

        $em = $this->getDoctrine()->getManager();

        $em->persist($favori);

        $em->flush();

        return new JsonResponse(
            array(
                'status' => 'OK'
            ),
            200
        );
    }


    /**
     * @Route("/api/delete/favoriarticle", name="api_delete_favoriarticle")
     */
    public function deleteFavoriArt(Request $request, FavoriArticleRepository $favArtRepo)
    {
        $utilisateur = $this->getUser();

        $favori = $favArtRepo->findOneBySomeField($utilisateur->getId(), $request->request->get('art_id'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($favori);
        $em->flush();

        return new JsonResponse(
            array(
                'status' => 'OK'
            ),
            200
        );
    }

    /**
    * @Route("/api/searchArticle")
    */
    public function searchArticle(Request $request, ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(['magasin' => $request->request->get('mag_id')]);
       // return $this->json($articles, Response::HTTP_OK);

        return $this->json($articles, Response::HTTP_OK, [], [
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object;
            }
        ]);
      
    }
}
