<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Avis;
use App\Entity\Vehicle;
use App\Repository\AvisRepository;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Uid\Uuid;






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




// Créer un trajet 
#[Route('/new-ride', methods: ['POST'])]
#[IsGranted('ROLE_DRIVER')]
public function createRide(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Validation basique
    $requiredFields = ['departure', 'arrival', 'date', 'time', 'vehicleId', 'price', 'seats'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            return new JsonResponse(['error' => "Le champ '$field' est requis."], 400);
        }
    }

    /** @var User $driver */
    $driver = $this->getUser();
    if (!$driver) {
        return new JsonResponse(['error' => 'Utilisateur non connecté.'], 401);
    }

    // Fusion date + heure
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time']);
    if (!$dateTime) {
        return new JsonResponse(['error' => 'Format de date ou d\'heure invalide.'], 400);
    }

    // Récupération du véhicule
    $vehicle = $em->getRepository(Vehicle::class)->find($data['vehicleId']);
    if (!$vehicle || $vehicle->getOwner()->getId() !== $driver->getId()) {
        return new JsonResponse(['error' => 'Véhicule introuvable ou non autorisé.'], 403);
    }

    // Création du trajet
    $ride = new Ride();
    $ride->setDriver($driver);
    $ride->setDeparture($data['departure']);
    $ride->setArrival($data['arrival']);
    $ride->setDate($dateTime);
    $ride->setVehicle($vehicle);
    $ride->setPrice((float) $data['price']);
    $ride->setAvailableSeats((int) $data['seats']);
    $ride->setInitialSeats((int) $data['seats']);

    $em->persist($ride);
    $em->flush();

    return new JsonResponse([
        'message' => 'Trajet créé avec succès',
        'ride_id' => $ride->getId()
    ], 201);
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
                'energy' => $vehicle->isElectric(),
            ] : null,
            'driver' => $ride->getDriver() ? [
                'id' => $ride->getDriver()->getId(),
                'pseudo' => $ride->getDriver()->getPseudo(),
                'email' => $ride->getDriver()->getEmail(),
                'extras' => $ride->getDriver()->getDriverPreferences() ?? [],
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
public function registerToRide(int $ride_id, Request $request, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        return $this->json(['error' => 'Utilisateur non connecté'], 401);
    }

    $ride = $em->getRepository(Ride::class)->find($ride_id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    $nbPassagers = 1; // par défaut 1
    $data = json_decode($request->getContent(), true);
    if (isset($data['nbPassagers']) && is_numeric($data['nbPassagers'])) {
        $nbPassagers = (int) $data['nbPassagers'];
    }

    $prixTotal = $ride->getPrice() * $nbPassagers;

    if ($ride->getAvailableSeats() < $nbPassagers) {
        return $this->json(['error' => 'Pas assez de places disponibles'], 400);
    }

    if ($user->getCredits() < $prixTotal) {
        return $this->json(['error' => 'Crédits insuffisants'], 400);
    }

    if ($ride->getPassengers()->contains($user)) {
        return $this->json(['message' => 'Déjà inscrit à ce trajet'], 200);
    }

    $ride->addPassenger($user);
    $ride->setAvailableSeats($ride->getAvailableSeats() - $nbPassagers);
    $user->setCredits($user->getCredits() - $prixTotal);

    $em->persist($ride);
    $em->persist($user);
    $em->flush();

    return $this->json(['success' => 'Réservation confirmée']);
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



//Rechercher un trajet
#[Route('/search', name: 'app_ride_search', methods: ['GET'])]
public function searchRides(Request $request, RideRepository $rideRepository, AvisRepository $avisRepo): JsonResponse
{
    $villeDepart = $request->query->get('villeDepart');
    $villeArrivee = $request->query->get('villeArrivee');
    $date = $request->query->get('date');
    $nbPassagers = $request->query->get('nbPassagers');

    $rides = $rideRepository->findAvailableRides($villeDepart, $villeArrivee, $date, $nbPassagers);

    $rideData = array_map(function ($ride) use ($avisRepo) {
        return [
            'id' => $ride->getId(),
            'departureCity' => $ride->getDeparture(),
            'arrivalCity' => $ride->getArrival(),
            'departureTime' => $ride->getDate()->format('H\hi'),
            'arrivalTime' => (clone $ride->getDate())->modify('+1 hour')->format('H\hi'),
            'duration' => '1h00',
            'price' => $ride->getPrice(),
            'availableSeats' => $ride->getAvailableSeats(),
            'extras' => implode(', ', array_keys(array_filter(
                $ride->getDriver()?->getDriverPreferences() ?? [],
                fn($v) => $v
            ))),
            'isElectric' => $ride->getVehicle()?->isElectric() ?? false,
            'driver' => [
                'pseudo' => $ride->getDriver()?->getPseudo() ?? 'Anonyme',
                'image' => $ride->getDriver()?->getImageUrl(),
                'rating' => round($avisRepo->getAverageRatingForDriver($ride->getDriver()), 1),
                'verified' => $ride->getDriver()?->isVerified() ?? false,
                'gender' => $ride->getDriver()?->getGender() ?? 'NO',
            ],
        ];
    }, $rides);

    return $this->json($rideData);
}



#[Route('/next-available', name: 'app_ride_next', methods: ['GET'])]
public function nextAvailable(Request $request, RideRepository $rideRepository, AvisRepository $avisRepo): JsonResponse
{
    $villeDepart = $request->query->get('villeDepart');
    $villeArrivee = $request->query->get('villeArrivee');
    $date = $request->query->get('date');
    $nbPassagers = $request->query->get('nbPassagers');

    $rides = $rideRepository->findNextAvailableRides($villeDepart, $villeArrivee, $date, $nbPassagers);

    $rideData = array_map(function ($ride) use ($avisRepo) {
        return [
            'id' => $ride->getId(),
            'departureCity' => $ride->getDeparture(),
            'arrivalCity' => $ride->getArrival(),
            'departureTime' => $ride->getDate()->format('H\hi'),
            'arrivalTime' => (clone $ride->getDate())->modify('+1 hour')->format('H\hi'),
            'duration' => '1h00',
            'price' => $ride->getPrice(),
            'availableSeats' => $ride->getAvailableSeats(),
            'extras' => implode(', ', array_keys(array_filter(
                $ride->getDriver()?->getDriverPreferences() ?? [],
                fn($v) => $v
            ))),
            'isElectric' => $ride->getVehicle()?->isElectric() ?? false,
            'driver' => [
                'pseudo' => $ride->getDriver()?->getPseudo() ?? 'Anonyme',
                'image' => $ride->getDriver()?->getImageUrl(),
                'rating' => round($avisRepo->getAverageRatingForDriver($ride->getDriver()), 1),
                'verified' => $ride->getDriver()?->isVerified() ?? false,
                'gender' => $ride->getDriver()?->getGender() ?? 'NO',
            ],
            'date' => $ride->getDate()->format('Y-m-d'),
        ];
    }, $rides);

    return $this->json($rideData);
}


// Terminer un trajet et envoyer des emails aux passagers
#[Route('/{id}/terminate', name: 'app_ride_terminate', methods: ['POST'])]
public function terminateRide(
    int $id,
    RideRepository $rideRepo,
    EntityManagerInterface $em,
    MailerInterface $mailer
): JsonResponse {
    $ride = $rideRepo->find($id);

    if (!$ride) {
        return $this->json(['error' => 'Trajet non trouvé'], 404);
    }

    $ride->setStatus('terminé');
    $em->flush();

    foreach ($ride->getPassengers() as $passenger) {
        $token = Uuid::v4()->toRfc4122();

        // Crée un avis vide avec le token
        $avis = new Avis();
        $avis->setRide($ride);
        $avis->setPassenger($passenger);
        $avis->setDriver($ride->getDriver());
        $avis->setToken($token);
        $avis->setStatus('à traiter');
        $avis->setIsValidated(false);
        $em->persist($avis);

        // Envoie l’email avec le lien vers ride-validated
        $email = (new TemplatedEmail())
            ->from('contact@ecoride.com')
            ->to($passenger->getEmail())
            ->subject('Merci de confirmer votre trajet EcoRide')
            ->htmlTemplate('emails/ride_feedback.html.twig')
            ->context([
                'ride' => $ride,
                'passenger' => $passenger,
                'link' => 'https://ecoride.com/ride-validated?token=' . $token
            ]);

        $mailer->send($email);
    }

    $em->flush();

    return $this->json(['success' => 'Trajet terminé. Les passagers ont été notifiés.']);
}

#[Route('/feedback/check', methods: ['GET'])]
#[IsGranted('PUBLIC_ACCESS')]
public function checkFeedback(Request $request, EntityManagerInterface $em): JsonResponse
{
    $token = $request->query->get('token');

    $avis = $em->getRepository(Avis::class)->findOneBy(['token' => $token]);

    if (!$avis || $avis->isValidated()) {
        return $this->json(['error' => 'Token invalide ou déjà utilisé'], 400);
    }

    return $this->json([
        'ride_id' => $avis->getRide()->getId(),
        'passenger' => $avis->getPassenger()->getPseudo()
    ]);
}





}
