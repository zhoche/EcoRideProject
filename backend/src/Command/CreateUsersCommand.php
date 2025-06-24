<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:test-users',
    description: 'Crée des utilisateurs avec rôles - admin, driver, employe, passenger',
)]
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
            ['admin@test.com',     'admin',      ['ROLE_ADMIN'], null],
            ['driver@test.com',    'driver',     ['ROLE_DRIVER'], [
                'Véhicule non-fumeur' => true,
                'Femmes uniquement' => false,
                'Animal de compagnie autorisé' => true,

            ]],
            ['employe@test.com',   'employe',    ['ROLE_EMPLOYE'], null],
            ['passenger@test.com', 'passenger',  ['ROLE_USER'], null],
            ['driver2@test.com',   'driver2',    ['ROLE_DRIVER', 'ROLE_USER'], [
                'Véhicule non-fumeur' => false,
                'Femmes uniquement' => true,
                'Animal de compagnie autorisé' => true,
            ]],
            ['driver3@test.com',   'driver3',    ['ROLE_DRIVER'], [
                'Véhicule non-fumeur' => true,
                'Femmes uniquement' => false,
                'Animal de compagnie autorisé' => false,
            ]],
            ['passenger2@test.com','passenger2', ['ROLE_USER'], null],
        ];

        foreach ($users as [$email, $pseudo, $roles, $preferences]) {
            $user = new User();
            $user->setEmail($email);
            $user->setPseudo($pseudo);
            $user->setRoles($roles);
            $user->setCredits(in_array('ROLE_ADMIN', $roles) ? 0 : 20);
            $user->setPassword($this->hasher->hashPassword($user, 'testpass'));

            if (in_array('ROLE_DRIVER', $roles) && $preferences !== null) {
                $user->setDriverPreferences($preferences);
            }

            $this->em->persist($user);
            $output->writeln("✅ Utilisateur $email avec rôle(s) " . implode(', ', $roles) . " créé.");
        }

        $this->em->flush();

        $output->writeln("🎉 Tous les utilisateurs ont été créés avec le mot de passe : testpass");
        return Command::SUCCESS;
    }
}
