<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class ApiLoginController extends AbstractController
{
    // #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    // public function login(
    //     Request $request,
    //     UserRepository $userRepository,
    //     UserPasswordHasherInterface $passwordHasher,
    //     JWTTokenManagerInterface $jwtManager
        
    // ): JsonResponse {
    //     $data = json_decode($request->getContent(), true);

    //     if (!isset($data['email'], $data['password'])) {
    //         return new JsonResponse(['error' => 'Email et mot de passe requis.'], 400);
    //     }

    //     $user = $userRepository->findOneBy(['email' => $data['email']]);
        
    //     if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
    //         return new JsonResponse(['error' => 'Identifiants invalides.'], 401);
    //     }

    //     $token = $jwtManager->create($user);
        
    //     return new JsonResponse([
    //         'user' => [
    //             'token' => $token,
    //             'id' => $user->getId(),
    //             'email' => $user->getEmail(),
    //             'role' => $user->getRoles()[0] ?? 'ROLE_USER'
    //         ]
    //     ]);
    // }
}
