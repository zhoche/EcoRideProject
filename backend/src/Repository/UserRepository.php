<?php
// src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByRole(string $role): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne tous les users ayant ROLE_EMPLOYE,
     * en filtrant **en PHP** pour éviter le LIKE sur du jsonb.
     *
     * @return User[]
     */
    public function findEmployes(): array
    {
        $filtered = array_filter(
            $this->findAll(),
            fn(User $u) => in_array('ROLE_EMPLOYE', $u->getRoles(), true)
        );
    
        return array_values($filtered);
    }
}
