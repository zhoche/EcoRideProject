<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[Route('/api/rides')]
class RideController extends AbstractController
{

    // Route pour tester l'utilisateur connecté
    #[Route('/test-user', methods: ['GET'])]
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

    // Route pour créer un trajet
    #[Route('/ride/create', methods: ['POST'])]
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




// Lister tous les trajets de l'utilisateur connecté

#[Route('/ride/list', methods: ['GET'])]
public function getAllUserRides(EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    // Récupérer les IDs des trajets
    $rideIDs = $user->getRideIDs() ?? [];

    if (empty($rideIDs)) {
        return $this->json([]);
    }

    // Rechercher tous les trajets correspondants
    $rides = $em->getRepository(Ride::class)->findBy([
        'id' => $rideIDs,
    ]);

    // Transformer les trajets en tableau
    $rideData = [];

    foreach ($rides as $ride) {
        $vehicle = $ride->getVehicle();

        $rideData[] = [
            'id' => $ride->getId(),
            'departure' => $ride->getDeparture(),
            'arrival' => $ride->getArrival(),
            'date' => $ride->getDate()?->format('Y-m-d H:i:s'),
            'availableSeats' => $ride->getAvailableSeats(),
            'price' => $ride->getPrice(),
            'vehicle' => $vehicle ? [
                'id' => $vehicle->getId(),
                'brand' => $vehicle->getBrand(),
                'model' => $vehicle->getModel(),
                'energy' => $vehicle->getEnergy(),
            ] : null,
        ];
    }

    return $this->json($rideData);
}



//S'inscrire à un trajet
#[Route('/ride/{ride_id}/register', methods: ['POST'])]
public function registerToRide(int $ride_id, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($ride_id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    // Vérifie s'il reste de la place
    if ($ride->getAvailableSeats() <= 0) {
        return $this->json(['error' => 'Aucune place disponible'], 400);
    }

    // Vérifie si l'utilisateur a assez de crédits
    if ($user->getCredits() < 1) {
        return $this->json(['error' => 'Crédits insuffisants'], 400);
    }

    // Vérifie s'il est déjà inscrit
    if ($ride->getPassengers()->contains($user)) {
        return $this->json(['message' => 'Déjà inscrit à ce trajet'], 200);
    }

    // Enregistre l'utilisateur comme passager
    $ride->addPassenger($user);
    $ride->setAvailableSeats($ride->getAvailableSeats() - 1);

    // Met à jour les crédits
    $user->setCredits($user->getCredits() - 1);

    // Sauvegarde l'ID du trajet dans user.rideIDs
    $user->addRideID($ride->getId());

    // Persistance
    $em->persist($ride);
    $em->persist($user);
    $em->flush();

    return $this->json(['success' => 'Inscription réussie']);
}

}
