<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;  // remplace HttpClient
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GeocodeController extends AbstractController
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    #[Route('/api/geocode', name: 'api_geocode', methods: ['GET'])]
    public function geocode(Request $request): JsonResponse
    {
        $text = $request->query->get('text');
        if (!$text) {
            return $this->json(['error' => 'Paramètre text manquant'], 400);
        }

        $apiKey = $this->getParameter('env(ORS_API_KEY)');
        $response = $this->client->request('GET', 'https://api.openrouteservice.org/geocode/search', [
            'headers' => ['Authorization' => $apiKey],
            'query'   => ['text' => $text],
        ]);

        if (200 !== $response->getStatusCode()) {
            return $this->json(['error' => 'ORS a renvoyé le code '.$response->getStatusCode()], $response->getStatusCode());
        }

        return $this->json($response->toArray());
    }
}
