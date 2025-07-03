<?php

namespace App\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
class AdminController extends AbstractController
{

    #[Route('/rides-per-day', name: 'admin_rides_per_day', methods: ['GET'])]
    public function getRidesPerDay(EntityManagerInterface $em): JsonResponse
    {
    $conn = $em->getConnection();

    $sql = "
        SELECT DATE(date) AS date, COUNT(*) AS total
        FROM ride
        GROUP BY DATE(date)
        ORDER BY date ASC
    ";

    $results = $conn->executeQuery($sql)->fetchAllAssociative();

    return $this->json($results);
}


#[Route('/credits-earned-per-day', name: 'admin_credits_per_day', methods: ['GET'])]
public function getCreditsPerDay(EntityManagerInterface $em): JsonResponse
{
    $conn = $em->getConnection();

    $sql = "
        SELECT 
            DATE(date) AS date, 
            SUM((initial_seats - available_seats) * 2) AS credits
        FROM ride
        GROUP BY DATE(date)
        ORDER BY date ASC
    ";

    $results = $conn->executeQuery($sql)->fetchAllAssociative();

    return $this->json($results);
}


#[Route('/credits-earned-total', name: 'admin_credits_total', methods: ['GET'])]
public function getTotalCreditsEarned(EntityManagerInterface $em): JsonResponse
{
    $conn = $em->getConnection();

    $sql = "
        SELECT SUM((initial_seats - available_seats) * 2) AS total_credits
        FROM ride
    ";

    $result = $conn->executeQuery($sql)->fetchOne(); 

    return $this->json(['totalCredits' => (int) $result]);
}





}