<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'create:test-users')]
class CreateUsersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = [
            ['ericdu19@test.com', 'ericdu19', ['ROLE_DRIVER'], [
                'Véhicule non-fumeur' => true,
                'Femmes uniquement' => false,
                'Animal de compagnie autorisé' => true,
            ], 'M'],
            ['fabienne@test.com', 'fabienne', ['ROLE_DRIVER', 'ROLE_USER'], [
                'Véhicule non-fumeur' => false,
                'Femmes uniquement' => true,
                'Animal de compagnie autorisé' => true,
            ], 'M'],
            ['kati@test.com', 'kati9', ['ROLE_DRIVER'], [
                'Véhicule non-fumeur' => true,
                'Femmes uniquement' => false,
                'Animal de compagnie autorisé' => false,
            ], 'F'],
        ];

        foreach ($users as [$email, $pseudo, $roles, $preferences, $gender]) {
            $user = new User();
            $user->setEmail($email)
                ->setPseudo($pseudo)
                ->setRoles($roles)
                ->setCredits(in_array('ROLE_ADMIN', $roles) ? 0 : 20)
                ->setGender($gender)
                ->setPassword($this->hasher->hashPassword($user, 'testpass'));

            if (in_array('ROLE_DRIVER', $roles) && $preferences) {
                $user->setDriverPreferences($preferences);
            }

            $this->em->persist($user);
            $output->writeln("Utilisateur $email créé avec rôles: " . implode(', ', $roles));
        }

        $this->em->flush();
        $output->writeln("Tous les utilisateurs ont été créés.");
        return Command::SUCCESS;
    }
}
