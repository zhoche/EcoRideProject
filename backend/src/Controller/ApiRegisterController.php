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
    
        // Validation basique
        if (!isset($data['email'], $data['password'], $data['pseudo'])) {
            return new JsonResponse(['error' => 'Données incomplètes.'], 400);
        }
    
        // Sanitize (protection XSS / injection)
        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $pseudo = htmlspecialchars(trim($data['pseudo']));
    
        // Rôles autorisés uniquement
        $allowedRoles = ['ROLE_USER', 'ROLE_EMPLOYE', 'ROLE_DRIVER'];
        $inputRoles = $data['roles'] ?? ['ROLE_USER'];
        $roles = array_filter($inputRoles, fn($r) => in_array($r, $allowedRoles));


        // Sexe
        $gender = $data['gender'] ?? 'NO';

        if (!in_array($gender, ['F', 'M', 'NO'])) {
            return new JsonResponse(['error' => 'Genre invalide.'], 400);
        }

    
        // Un conducteur est aussi passager par défaut
        if (in_array('ROLE_DRIVER', $roles) && !in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        if (in_array('ROLE_ADMIN', $inputRoles)) {
            return new JsonResponse(['error' => 'Création de compte administrateur interdite.'], 403);
        }

        if (empty($roles)) {
            return new JsonResponse(['error' => 'Aucun rôle valide fourni.'], 400);
        }
    
        $user = new User();
        $user->setEmail($email);
        $user->setPseudo($pseudo);
        $user->setRoles($roles);
        
    
        // Crédit uniquement pour utilisateurs/passagers/conducteurs
        if (in_array('ROLE_USER', $roles) || in_array('ROLE_DRIVER', $roles)) {
            $user->setCredits(20);
        } else {
            $user->setCredits(0);
        }
    
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
    
        $em->persist($user);
        $em->flush();
    
        return new JsonResponse(['message' => 'Inscription réussie'], 201);
    }
    
    
}
