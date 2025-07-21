<?php

namespace App\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\SuspendedUser;
use App\Repository\SuspendedUserRepository;
use Psr\Log\LoggerInterface;




#[Route('/api/admin')]
class AdminController extends AbstractController
{


    public function __construct(private LoggerInterface $logger) {}



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


#[Route('/employee-list', name: 'api_employees_list', methods: ['GET'])]
    public function getEmployees(UserRepository $userRepository): JsonResponse
    {
        try {
            $employees = $userRepository->findEmployes();
        } catch (\Throwable $e) {
            $this->logger->error('Erreur findEmployes: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            return $this->json(['error' => 'Internal Server Error'], 500);
        }

        // Mapping vers le format JSON attendu par le front
        $data = array_map(fn($u) => [
            'id'        => $u->getId(),
            'email'     => $u->getEmail(),
            'pseudo'    => $u->getPseudo(),
            'createdAt' => $u->getCreatedAt()?->format('Y-m-d'),
        ], $employees);

        return $this->json($data);
    }





#[Route('/employee-delete/{id}', name: 'admin_employee_delete', methods: ['DELETE'])]
public function deleteEmployee(int $id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
{
    $employee = $userRepository->find($id);

    if (!$employee) {
        return $this->json(['message' => 'Employé non trouvé'], 404);
    }

    $em->remove($employee);
    $em->flush();

    return $this->json(['message' => 'Employé supprimé avec succès']);
}


#[Route('/suspended-user', name: 'admin_suspend_user', methods: ['POST'])]
public function suspendUser(Request $request, EntityManagerInterface $em, UserRepository $userRepo): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $role = $data['role'] ?? 'Employe';

    if (strtoupper($role) === 'ADMIN' || strtoupper($role) === 'ROLE_ADMIN') {
        return $this->json(['error' => '❌ Suspension du rôle ADMIN impossible.'], 403);
    }

    $user = $userRepo->findOneBy(['email' => $data['email']]);

    if (!$user) {
        return $this->json(['error' => '❌ Utilisateur non trouvé.'], 404);
    }

    $suspended = new SuspendedUser();
    $suspended->setEmail($user->getEmail());
    $suspended->setPseudo($user->getPseudo());
    $suspended->setRole($role); 
    $suspended->setSuspendedAt(new \DateTimeImmutable());

    $em->persist($suspended);
    $em->remove($user);
    $em->flush();

    return $this->json(['message' => '✅ Compte suspendu et retiré de la base active.']);
}



#[Route('/suspended-users-list', name: 'admin_suspended_users', methods: ['GET'])]
public function getSuspendedUsers(SuspendedUserRepository $repo): JsonResponse
{
    $users = $repo->findAll();

    $data = array_map(function ($user) {
        return [
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'suspendedAt' => $user->getSuspendedAt()?->format('Y-m-d'),
        ];
    }, $users);

    return $this->json($data);
}



#[Route('/suspended-emails', name: 'admin_suspended_emails', methods: ['GET'])]
public function getSuspendedEmails(SuspendedUserRepository $repo): JsonResponse
{
    $emails = $repo->createQueryBuilder('s')
        ->select('s.email')
        ->getQuery()
        ->getSingleColumnResult();

    return $this->json($emails);
}
}