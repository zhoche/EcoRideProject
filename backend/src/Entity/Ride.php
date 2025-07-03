<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{


    //{ id: 1, driverID: 1, departure: 'A', arrival: 'B', availableSeats: 5, price: 3, date: '2023-10-01T10:00:00Z', vehicle: 'vehiculeID', idPassengers: ["id1", "id2"] },

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ridesAsDriver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ride:read'])]
    private ?string $departure = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ride:read'])]
    private ?string $arrival = null;

    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?\DateTime $date = null;

    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?int $availableSeats = null;

    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Vehicle::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ride:read'])]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ridesAsPassenger')]
    #[Groups(['ride:read'])]
    private Collection $passengers;

    #[ORM\Column(type: 'integer')]
    private ?int $initialSeats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(User $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function __construct()
    {
        $this->passengers = new ArrayCollection();
    }

    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(User $user): static
    {
        if (!$this->passengers->contains($user)) {
            $this->passengers->add($user);
            $user->getRidesAsPassenger()->add($this); 
        }
    
        return $this;
    }

    public function removePassenger(User $user): static
    {
        if ($this->passengers->removeElement($user)) {
            $user->getRidesAsPassenger()->removeElement($this);
        }

        return $this;
    }



    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): static
    {
        $this->departure = $departure;
        return $this;
    }


    public function getArrival(): ?string
    {
        return $this->arrival;
    }

    public function setArrival(string $arrival): static
    {
        $this->arrival = $arrival;
        return $this;
    }


    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;
        return $this;
    }


    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;
        return $this;
    }


    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }


    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;
        return $this;
    }




    public function getInitialSeats(): ?int
    {
        return $this->initialSeats;
    }

    public function setInitialSeats(int $initialSeats): self
    {
        $this->initialSeats = $initialSeats;
        return $this;
    }



}
