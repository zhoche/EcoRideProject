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



public function findAvailableRides(string $villeDepart, string $villeArrivee, string $date, int $nbPassagers)
{
    $startOfDay = (new \DateTime($date))->setTime(0, 0, 0);
    $endOfDay = (new \DateTime($date))->setTime(23, 59, 59);

    $qb = $this->createQueryBuilder('r')
        ->where('r.departure = :villeDepart')
        ->andWhere('r.arrival = :villeArrivee')
        ->andWhere('r.date BETWEEN :start AND :end')
        ->andWhere('r.availableSeats >= :nbPassagers')
        ->orderBy('r.date', 'ASC');

    $qb
        ->setParameter('villeDepart', $villeDepart)
        ->setParameter('villeArrivee', $villeArrivee)
        ->setParameter('start', $startOfDay)
        ->setParameter('end', $endOfDay)
        ->setParameter('nbPassagers', $nbPassagers);

    return $qb->getQuery()->getResult();
}



public function findNextAvailableRides(string $villeDepart, string $villeArrivee, string $date, int $nbPassagers): array
{
    $endOfDay = (new \DateTime($date))->setTime(23, 59, 59);

    $qb = $this->createQueryBuilder('r')
        ->andWhere('r.departure = :villeDepart')
        ->andWhere('r.arrival = :villeArrivee')
        ->andWhere('r.date > :endOfDay') // ← uniquement après le jour donné
        ->andWhere('r.availableSeats >= :nbPassagers')
        ->orderBy('r.date', 'ASC')
        ->setMaxResults(3);

    $qb
        ->setParameter('villeDepart', $villeDepart)
        ->setParameter('villeArrivee', $villeArrivee)
        ->setParameter('endOfDay', $endOfDay)
        ->setParameter('nbPassagers', $nbPassagers);

    return $qb->getQuery()->getResult();
}







}