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
        // [email, pseudo, roles, preferences|null, gender, imageUrl|null, rating, isVerified]
        $users = [
            // EMPLOYEE
            ['employee2@example.com', 'employee2', ['ROLE_EMPLOYEE'], null, 'M', 'images/Profil_Employe.png', null, true],

            // DRIVER / USER
            ['driver1@example.com', 'jerome', ['ROLE_DRIVER','ROLE_USER'], [
                'Véhicule non-fumeur'          => true,
                'Femmes uniquement'            => false,
                'Animal de compagnie autorisé' => true,
            ], 'M', 'images/Profil_Jerome.png', 4.5, true],
            ['driver2@example.com', 'rosalie', ['ROLE_DRIVER','ROLE_USER'], [
                'Véhicule non-fumeur'          => false,
                'Femmes uniquement'            => true,
                'Animal de compagnie autorisé' => false,
            ], 'F', 'images/Profil_Passager-Conducteur.png', 4, false],
            ['driver3@example.com', 'francky', ['ROLE_DRIVER','ROLE_USER'], [
                'Véhicule non-fumeur'          => true,
                'Femmes uniquement'            => false,
                'Animal de compagnie autorisé' => false,
            ], 'M', 'images/Profil_Francky.png', 4, true],
            ['driver4@example.com', 'kati', ['ROLE_DRIVER','ROLE_USER'], [
                'Véhicule non-fumeur'          => false,
                'Femmes uniquement'            => false,
                'Animal de compagnie autorisé' => true,
            ], 'F', 'images/Profil_Kati.png', 5, true],
            ['driver5@example.com', 'anthony', ['ROLE_DRIVER','ROLE_USER'], [
                'Véhicule non-fumeur'          => false,
                'Femmes uniquement'            => false,
                'Animal de compagnie autorisé' => true,
            ], 'F', 'images/Profil_Anthony.png', 5, true],

            // USER
            ['passenger@example.com', 'kati', ['ROLE_USER'], null, 'F', 'images/Profil_Kati.png', 5, true],
            ['passenger2@example.com', 'alicia', ['ROLE_USER'], null, 'F', 'images/Profil_Alicia.png', 5, true],

        ];

        foreach ($users as [
            $email,
            $pseudo,
            $roles,
            $preferences,
            $gender,
            $imageUrl,
            $rating,
            $isVerified
        ]) {
            $user = new User();
            $user->setEmail($email)
                 ->setPseudo($pseudo)
                 ->setRoles($roles)
                 ->setCredits(in_array('ROLE_ADMIN', $roles) ? 0 : 20)
                 ->setGender($gender)
                 ->setCreatedAt(new \DateTimeImmutable())
                 ->setIsVerified($isVerified)
                 ->setRating($rating)
                 ->setImage($imageUrl) 
                 ->setPassword($this->hasher->hashPassword($user, 'Testpass1234!'));

            if (in_array('ROLE_DRIVER', $roles) && is_array($preferences)) {
                $user->setDriverPreferences($preferences);
            }

            $this->em->persist($user);
            $output->writeln(sprintf(
                'Utilisateur <info>%s</info> créé (rôles: <comment>%s</comment>)',
                $email,
                implode(', ', $roles)
            ));
        }

        $this->em->flush();
        $output->writeln('<info>Tous les utilisateurs ont été créés.</info>');

        return Command::SUCCESS;
    }
}
