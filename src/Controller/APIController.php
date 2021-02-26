<?php

namespace App\Controller;

use Amp\Http\Status;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
}
