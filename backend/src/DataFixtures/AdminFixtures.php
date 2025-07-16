<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@ecoride.local');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPseudo('Admin');
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'Admin1234!')
        );
        $admin->setIsVerified(true);
        $manager->persist($admin);
        $manager->flush();
    }
}
