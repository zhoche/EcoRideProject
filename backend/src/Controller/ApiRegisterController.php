<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiRegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST', 'OPTIONS'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 200);
        }
    
        $data = json_decode($request->getContent(), true);

        if (isset($data['roles']) && in_array('ROLE_ADMIN', $data['roles'])) {
            return new JsonResponse(['error' => 'Création de compte administrateur interdite.'], 403);
        }
    
        if (!isset($data['email'], $data['password'], $data['pseudo'])) {
            return new JsonResponse(['error' => 'Données incomplètes.'], 400);
        }
    
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPseudo($data['pseudo']);
        $user->setCredits(20);
        $user->setRoles(['ROLE_USER']);
    
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
    
        $em->persist($user);
        $em->flush();
    
        return new JsonResponse(['message' => 'Inscription réussie'], 201);
    }
    
}
