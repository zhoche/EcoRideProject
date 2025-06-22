<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Entity\Ride;
use App\Entity\Vehicle;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    //{ id: 1, pseudo: 'Alice', password: 'password123', email: 'alice@gmail.com', role: 'user', credits: 20, rideIDs: ["id1", "id2"], vehiculeIDs: ["vehiculeID"], driverPreferences: {fumer: false, animaux: true} },

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'integer')]
    private int $credits = 20;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rideIDs = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $driverPreferences = [];

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Vehicle::class)]
    private Collection $vehicles;


    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getCredits(): int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;
        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setOwnerID($this->getId());
        }
    
        return $this; 
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            if ($vehicle->getOwnerID() === $this->getId()) {
                $vehicle->setOwnerID(null);
            }
        }
    
        return $this;
    }


    public function getRideIDs(): ?array
    {
        return $this->rideIDs;
    }

    public function setRideIDs(?array $rideIDs): static
    {
        $this->rideIDs = $rideIDs;
        return $this;
    }

    public function addRideID(int $rideID): static
    {
        if (!in_array($rideID, $this->rideIDs ?? [])) {
            $this->rideIDs[] = $rideID;
        }
        return $this;
    }

    public function removeRideID(int $rideID): static
    {
        $this->rideIDs = array_filter($this->rideIDs ?? [], fn($id) => $id !== $rideID);
        return $this;
    }

    
    public function getDriverPreferences(): ?array
    {
        return $this->driverPreferences;
    }

    public function setDriverPreferences(?array $preferences): static
    {
        $this->driverPreferences = $preferences;
        return $this;
    }

}
