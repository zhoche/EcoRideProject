<?php
// src/Controller/GeocodeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GeocodeController extends AbstractController
{
    #[Route('/api/geocode', name: 'api_geocode', methods: ['GET'])]
    public function geocode(Request $request): JsonResponse
    {
        $text = $request->query->get('text');
        if (!$text) {
            return $this->json(['error' => 'Paramètre text manquant'], 400);
        }

        $client = HttpClient::create();
        $apiKey = $this->getParameter('env(ORS_API_KEY)');
        $url = 'https://api.openrouteservice.org/geocode/search';
        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => $apiKey],
            'query'   => ['text' => $text],
        ]);

        if (200 !== $response->getStatusCode()) {
            return $this->json(['error' => 'Erreur ORS'], $response->getStatusCode());
        }

        $data = $response->toArray();
        return $this->json($data);
    }
}
