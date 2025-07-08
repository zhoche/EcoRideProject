<?php

namespace App\Controller;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vehicles')]
class VehicleController extends AbstractController
{
    #[Route('/user', name: 'get_user_vehicles', methods: ['GET'])]
    public function getUserVehicles(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $vehicles = $em->getRepository(Vehicle::class)->findBy(['owner' => $user]);

        $data = array_map(fn($v) => [
            'id' => $v->getId(),
            'label' => $v->getBrand() . ' ' . $v->getModel() . ' (' . $v->getPlateNumber() . ')',
        ], $vehicles);

        return $this->json($data);
    }
}
