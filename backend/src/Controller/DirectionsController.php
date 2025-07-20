<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DirectionsController extends AbstractController
{
    public function __construct(private HttpClientInterface $client) {}

    #[Route('/api/directions', name: 'api_directions', methods: ['POST'])]
    public function directions(Request $request, HttpClientInterface $client): JsonResponse
    {
    $payload = json_decode($request->getContent(), true);
    if (!isset($payload['coordinates']) || count($payload['coordinates']) !== 2) {
        return $this->json(['error' => 'Mauvais payload'], 400);
    }

    [$start, $end] = $payload['coordinates'];
    $coords = sprintf(
        '%F,%F;%F,%F',
        $start[0], $start[1],
        $end[0],   $end[1]
    );

    $response = $client->request('GET', "https://router.project-osrm.org/route/v1/driving/{$coords}", [
        'query' => ['overview' => 'full', 'geometries' => 'geojson']
    ]);

    if (200 !== $response->getStatusCode()) {
        return $this->json(['error' => 'OSRM a renvoyé '.$response->getStatusCode()], $response->getStatusCode());
    }

    $data = $response->toArray();
    if (empty($data['routes'])) {
        return $this->json(['error' => 'Aucun itinéraire trouvé'], 404);
    }

    // Renvoie directement le GeoJSON de la route
    return $this->json($data['routes'][0]['geometry']);
}

}
