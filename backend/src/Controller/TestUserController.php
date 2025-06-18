<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestUserController extends AbstractController
{
    #[Route('/api/test-user', name: 'test_user')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('fakepassword');
        $user->setPseudo('Testeur');
        $user->setRoles(['ROLE_USER']);
        // On ne définit pas credits → doit valoir 20 automatiquement

        $em->persist($user);
        $em->flush();

        return new Response('Utilisateur créé avec crédits : ' . $user->getCredits());
    }
}
