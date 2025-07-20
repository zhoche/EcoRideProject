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
    public function geocode(Request $request, HttpClientInterface $client): JsonResponse
    {
        $text = $request->query->get('text');
        if (!$text) {
            return $this->json(['error' => 'Paramètre text manquant'], 400);
        }
    
        $response = $client->request('GET', 'https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q'      => $text,
                'format' => 'json',
                'limit'  => 1,
            ],
            'headers' => [
                'User-Agent' => 'EcoRide/1.0 (+https://ecoride.com)'
            ]
        ]);
    
        if (200 !== $response->getStatusCode()) {
            return $this->json(['error' => 'Nominatim a renvoyé '.$response->getStatusCode()], $response->getStatusCode());
        }
    
        $data = $response->toArray();
        if (empty($data)) {
            return $this->json(['error' => 'Aucun résultat'], 404);
        }
    
        // Nominatim renvoie lat/lon en chaîne
        return $this->json([
            'lat' => (float)$data[0]['lat'],
            'lon' => (float)$data[0]['lon'],
        ]);
    }
    
}
