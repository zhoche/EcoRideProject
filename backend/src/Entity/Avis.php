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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "driver_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $driver = null;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "passenger_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $passenger = null;
    
    #[ORM\ManyToOne(targetEntity: Ride::class)]
    #[ORM\JoinColumn(name: "ride_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Ride $ride = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isValidated = false;







    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRide(): ?Ride
    {
        return $this->ride;
    }
    
    public function setRide(?Ride $ride): static
    {
        $this->ride = $ride;
        return $this;
    }
    
    public function getDriver(): ?User
    {
        return $this->driver;
    }
    
    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;
        return $this;
    }
    
    public function getPassenger(): ?User
    {
        return $this->passenger;
    }
    
    public function setPassenger(?User $passenger): static
    {
        $this->passenger = $passenger;
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


    public function getToken(): ?string
    {
        return $this->token;
    }
    
    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }


        public function isValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $validated): self
    {
        $this->isValidated = $validated;
        return $this;
    }
}
