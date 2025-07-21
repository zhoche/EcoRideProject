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
            ['employee1@example.com', 'employee1', ['ROLE_EMPLOYE'], null, 'M', 'images/Profil_Employe.png', null, true],
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
