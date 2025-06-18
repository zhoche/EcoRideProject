<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;

#[Route('/api/rides')]
class RideController extends AbstractController
{
    // Route pour tester l'utilisateur connecté
    #[Route('/api/test-user', methods: ['GET'])]
    public function testUser(): JsonResponse
    {
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['message' => 'Utilisateur non connecté'], 401);
    }

    return $this->json([
        'email' => $user->getUserIdentifier(),
        'roles' => $user->getRoles()
    ]);
}
    // Route pour lister les trajets
    #[Route('', methods: ['GET'])]
    public function index(RideRepository $rideRepo): JsonResponse
    {
        $rides = $rideRepo->findAll();
        return $this->json($rides);
    }

    // Route pour créer un trajet
    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserRepository $userRepo,
        VehicleRepository $vehicleRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);


        // 1. Récupération de l'utilisateur connecté
    $driver = $this->getUser();
    if (!$driver) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    if (!in_array('ROLE_CHAUFFEUR', $driver->getRoles())) {
        return $this->json(['error' => 'Vous devez être chauffeur pour créer un trajet'], 403);
    }

    // 2. Vérification des données de base
    if (!isset($data['price']) || !is_numeric($data['price'])) {
        return $this->json(['error' => 'Le prix est requis et doit être un nombre'], 400);
    }

    if (!isset($data['available_seats']) || !is_numeric($data['available_seats'])) {
        return $this->json(['error' => 'Nombre de places disponible requis'], 400);
    }

    // 3. Vérification du véhicule
    $vehicle = $vehicleRepo->find($data['vehicle_id'] ?? null);
    if (!$vehicle || $vehicle->getOwner() !== $driver) {
        return $this->json(['error' => 'Véhicule invalide ou non autorisé'], 400);
    }

    // 4. Création du trajet
    $ride = new Ride();
    $ride->setDeparture($data['departure'] ?? null);
    $ride->setArrival($data['arrival'] ?? null);
    $ride->setDate(new \DateTime($data['date'] ?? 'now'));
    $ride->setPrice((float) $data['price']);
    $ride->setAvailableSeats((int) $data['available_seats']);
    $ride->setDriverRole($driver);
    $ride->setVehicle($vehicle);

    // 5. Validation
    $errors = $validator->validate($ride);
    if (count($errors) > 0) {
        return $this->json(['errors' => (string) $errors], 400);
    }

    // 6. Persistance
    $em->persist($ride);
    $em->flush();

        return $this->json(['id' => $ride->getId()], 201);
    }


    // Route pour rejoindre un trajet
    #[Route('/api/rides/{id}/join', methods: ['POST'])]
public function joinRide(
    int $id,
    EntityManagerInterface $em
): JsonResponse {
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Authentification requise'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet introuvable'], 404);
    }

    if ($ride->getAvailableSeats() <= 0) {
        return $this->json(['error' => 'Aucune place disponible'], 400);
    }

    if ($user->getCredits() < 1) {
        return $this->json(['error' => 'Crédits insuffisants'], 400);
    }

    if ($ride->getPassengers()->contains($user)) {
        return $this->json(['message' => 'Déjà inscrit à ce trajet'], 200);
    }

    // Participation
    $ride->addPassenger($user);
    $ride->setAvailableSeats($ride->getAvailableSeats() - 1);
    $user->setCredits($user->getCredits() - 1);

    $em->persist($ride);
    $em->persist($user);
    $em->flush();

    return $this->json(['message' => 'Participation confirmée']);
}

}
