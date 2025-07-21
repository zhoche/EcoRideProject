<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'create:admin')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = 'admin@ecoride.local';
        $pseudo = 'Admin';
        $roles = ['ROLE_ADMIN'];
        $plainPassword = 'Admin1234!';

        // Vérification idempotente : on skippe si déjà en base
        $repo = $this->em->getRepository(User::class);
        if ($repo->findOneBy(['email' => $email])) {
            $output->writeln("L’administrateur {$email} existe déjà, rien à faire.");
            return Command::SUCCESS;
        }

        // Création
        $admin = new User();
        $admin
            ->setEmail($email)
            ->setPseudo($pseudo)
            ->setRoles($roles)
            ->setGender('F')
            ->setCredits(0)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setIsVerified(true)
            ->setPassword(
                $this->hasher->hashPassword($admin, $plainPassword)
            );

        $this->em->persist($admin);
        $this->em->flush();

        $output->writeln("✅  Compte admin {$email} créé avec succès.");
        return Command::SUCCESS;
    }
}
