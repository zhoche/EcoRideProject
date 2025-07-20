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
    public function directions(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['coordinates'])) {
            return $this->json(['error' => 'Bad payload'], 400);
        }

        $response = $this->client->request('POST', 'https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
            'headers' => [
                'Authorization' => $this->getParameter('env(ORS_API_KEY)'),
                'Content-Type'  => 'application/json',
            ],
            'json' => $data,
        ]);

        if (200 !== $response->getStatusCode()) {
            return $this->json(['error' => 'ORS code '.$response->getStatusCode()], $response->getStatusCode());
        }

        return $this->json($response->toArray());
    }
}
