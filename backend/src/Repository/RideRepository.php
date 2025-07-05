<?php

namespace App\Repository;

use App\Entity\Ride;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class RideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }


    public function findByRole(string $role): array
{
    $qb = $this->createQueryBuilder('u');
    return $qb
        ->where('JSON_CONTAINS(u.roles, :role) = 1')
        ->setParameter('role', json_encode($role))
        ->getQuery()
        ->getResult();
}



}