<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{

    //{ id: 1, rideID: 1, driverID: 1, passengerID: 1, rating: 5, comment: 'Great ride!', status: 'a traiter'}


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rideID = null;

    #[ORM\Column]
    private ?int $driverID = null;

    #[ORM\Column]
    private ?int $passengerID = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRideID(): ?int
    {
        return $this->rideID;
    }

    public function setRideID(int $rideID): static
    {
        $this->rideID = $rideID;

        return $this;
    }

    public function getDriverID(): ?int
    {
        return $this->driverID;
    }

    public function setDriverID(int $driverID): static
    {
        $this->driverID = $driverID;

        return $this;
    }

    public function getPassengerID(): ?int
    {
        return $this->passengerID;
    }

    public function setPassengerID(int $passengerID): static
    {
        $this->passengerID = $passengerID;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
