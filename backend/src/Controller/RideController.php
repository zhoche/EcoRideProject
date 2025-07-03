<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Avis;
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
    #[Route('/create', methods: ['POST'])]
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
    $ride->setInitialSeats((int) $data['available_seats']);
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

#[Route('/list', methods: ['GET'])]
public function getAllUserRides(EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ridesAsDriver = $user->getRidesAsDriver()->toArray();
    $ridesAsPassenger = $user->getRidesAsPassenger()->toArray();

    $rides = array_merge($ridesAsDriver, $ridesAsPassenger);

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
            'driver' => $ride->getDriver() ? [
                'id' => $ride->getDriver()->getId(),
                'pseudo' => $ride->getDriver()->getPseudo(),
                'email' => $ride->getDriver()->getEmail(),
            ] : null,
        ];
    }

    // Historique 
    $creditHistory = [
        ['date' => '20/06', 'value' => 10],
        ['date' => '22/06', 'value' => 15],
        ['date' => '24/06', 'value' => 18],
        ['date' => '26/06', 'value' => $user->getCredits()],
    ];



    // Préférences
    $rawPrefs = $user->getDriverPreferences() ?? [];

    $preferences = array_map(function($key, $value) {
        if ($value) {
            return match($key) {
                default => ucfirst($key)
            };
        }
        return null;
    }, array_keys($rawPrefs), $rawPrefs);

    return $this->json([
        'credits' => $user->getCredits(),
        'rides' => $rideData,
        'creditHistory' => $creditHistory,
        'preferences' => array_values(array_filter($preferences)),
    ]);
}





//S'inscrire à un trajet
#[Route('/{ride_id}/register', methods: ['POST'])]
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

    if ($ride->getAvailableSeats() <= 0) {
        return $this->json(['error' => 'Aucune place disponible'], 400);
    }

    if ($user->getCredits() < 1) {
        return $this->json(['error' => 'Crédits insuffisants'], 400);
    }

    if ($ride->getPassengers()->contains($user)) {
        return $this->json(['message' => 'Déjà inscrit à ce trajet'], 200);
    }

    $ride->addPassenger($user);
    $ride->setAvailableSeats($ride->getAvailableSeats() - 1);
    $user->setCredits($user->getCredits() - 1);

    $em->persist($ride);
    $em->persist($user);
    $em->flush();

    return $this->json(['success' => 'Inscription réussie']);
}




// Se désinscrire d'un trajet
#[Route('/{ride_id}/unregister', methods: ['POST'])]
public function unregisterFromRide(int $ride_id, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($ride_id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    if (!$ride->getPassengers()->contains($user)) {
        return $this->json(['message' => 'Utilisateur non inscrit à ce trajet'], 400);
    }

    $ride->removePassenger($user);

    $ride->setAvailableSeats($ride->getAvailableSeats() + 1);
    $user->setCredits($user->getCredits() + 1);

    $em->persist($ride);
    $em->persist($user);
    $em->flush();

    return $this->json(['success' => 'Désinscription réussie']);
}



// Supprimer un trajet
#[Route('/{ride_id}/delete', methods: ['POST'])]
public function deleteRide(int $ride_id, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($ride_id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    // Vérifie que l'utilisateur est bien le conducteur ET le propriétaire du véhicule
    if (
        $ride->getDriver()?->getId() !== $user->getId() ||
        $ride->getVehicle()?->getOwner()?->getId() !== $user->getId()
    ) {
        return $this->json(['error' => 'Vous n\'êtes pas autorisé à supprimer ce trajet'], 403);
    }

    $em->remove($ride);
    $em->flush();

    return $this->json(['success' => 'Trajet supprimé avec succès']);
}





// Modifier un trajet
#[Route('/{ride_id}/update', methods: ['POST'])]
public function updateRide(
    int $ride_id,
    Request $request,
    EntityManagerInterface $em,
    ValidatorInterface $validator
): JsonResponse {
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($ride_id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    // Autorisation : il faut être le conducteur ET le propriétaire du véhicule
    if (
        $ride->getDriver()?->getId() !== $user->getId() ||
        $ride->getVehicle()?->getOwner()?->getId() !== $user->getId()
    ) {
        return $this->json(['error' => 'Vous n’êtes pas autorisé à modifier ce trajet'], 403);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['departure'])) {
        $ride->setDeparture($data['departure']);
    }

    if (isset($data['arrival'])) {
        $ride->setArrival($data['arrival']);
    }

    if (isset($data['date'])) {
        $ride->setDate(new \DateTime($data['date']));
    }

    if (isset($data['available_seats']) && is_numeric($data['available_seats'])) {
        $ride->setAvailableSeats((int) $data['available_seats']);
    }

    if (isset($data['price']) && is_numeric($data['price'])) {
        $ride->setPrice((float) $data['price']);
    }

    $errors = $validator->validate($ride);
    if (count($errors) > 0) {
        return $this->json(['errors' => (string) $errors], 400);
    }

    $em->flush();

    return $this->json(['success' => 'Trajet mis à jour avec succès']);
}





// Donner un avis sur un trajet
#[Route('/feedback', methods: ['POST'])]
public function giveFeedback(Request $request, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $data = json_decode($request->getContent(), true);

    if (!isset($data['ride_id'], $data['rating'])) {
        return $this->json(['error' => 'Données incomplètes'], 400);
    }

    $ride = $em->getRepository(Ride::class)->find($data['ride_id']);
    if (!$ride) {
        return $this->json(['error' => 'Trajet introuvable'], 404);
    }

    // Vérifie que l'utilisateur était passager du trajet
    if (!$ride->getPassengers()->contains($user)) {
        return $this->json(['error' => 'Vous n\'avez pas participé à ce trajet'], 403);
    }

    // Crée et enregistre l'avis
    $avis = new Avis();
    $avis->setRide($ride);
    $avis->setPassenger($user);
    $avis->setDriver($ride->getDriver()); 
    $avis->setRating((int)$data['rating']);
    $avis->setComment($data['comment'] ?? null);
    $avis->setStatus('à traiter');

    $em->persist($avis);
    $em->flush();

    return $this->json(['success' => 'Avis enregistré']);
}




}
