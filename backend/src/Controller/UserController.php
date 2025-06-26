<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class UserController extends AbstractController
{
    #[Route('/api/redirect-role', name: 'redirect_role', methods: ['GET'])]
    public function redirectRole(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        $roles = $user->getRoles();
        $role = $roles[0]; 

        $redirectMap = [
            'ROLE_ADMIN'   => '/profile-admin',
            'ROLE_EMPLOYE' => '/profile-employe',
            'ROLE_CHAUFFEUR' => '/profile-driver',
            'ROLE_PASSAGER' => '/profile-passenger',
        ];

        $redirectTo = $redirectMap[$role] ?? '/';

        return $this->json([
            'role' => $role,
            'redirectTo' => $redirectTo,
        ]);
    }
}
