<?php

namespace App\Controller;

use Amp\Http\Client\Request as ClientRequest;
use Amp\Http\Status;
use App\Entity\FavoriMagasin;
use App\Repository\FavoriMagasinRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIController extends AbstractController
{
    /**
     * @Route("/api/get/searchAround", name="API_GET_SearchAround")
     */
    public function searchAround(MagasinRepository $magasinRepository, UtilisateurController $utilisateurController, Request $request): Response
    {
        $localisation = new LocalisationVector($request->get('latitude'), $request->get('longitude'));
        $magasins = $magasinRepository->searchAround($localisation->longitude, $localisation->latitude);

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
        $localisation = new LocalisationVector($request->get('latitude'), $request->get('longitude'));
        $magasins = $magasinRepository->searchAround($localisation->longitude, $localisation->latitude);

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
    public function favoriMag(FavoriMagasinRepository $favoriMagRepo)
    {

        $utilisateur = $this->getUser();

        $favori = $favoriMagRepo->findByUserId($utilisateur->getId());

        dump($favori);

        return $this->json($favori, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['localisation', 'magasins', 'typeMagasin', 'statistiqueMagasin', 'etat'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    /**
     * @Route("/api/set/favorimag", name="API_SET_FavoriMag")
     */
    public function deleteFavoriMag(Request $request)
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
}
