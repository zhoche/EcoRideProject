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
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;





#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    //{ id: 1, pseudo: 'Alice', password: 'password123', email: 'alice@gmail.com', role: 'user', credits: 20, rideIDs: ["id1", "id2"], vehiculeIDs: ["vehiculeID"], driverPreferences: {fumer: false, animaux: true} },

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\S]{8,}$/',
        message: "Le mot de passe doit contenir au moins 8 caractères, une lettre, un chiffre et un caractère spécial."
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ride:read'])]
    #[Assert\NotBlank(message: "Le pseudo est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le pseudo doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le pseudo ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull(message: "Les rôles doivent être définis.")]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 2)]
    #[Assert\Choice(
        choices: ['F', 'M', 'NO'],
        message: "Le genre doit être 'F', 'M' ou 'NO'."
    )]
    private ?string $gender = 'NO';

    #[ORM\Column(type: 'integer')]
    private int $credits = 20;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $driverPreferences = [];

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Vehicle::class)]
    private Collection $vehicles;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Ride::class)]
    private Collection $ridesAsDriver;

    #[ORM\ManyToMany(targetEntity: Ride::class, mappedBy: 'passengers')]
    private Collection $ridesAsPassenger;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['ride:read'])]
    private ?string $image = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['ride:read'])]
    private ?float $rating = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['ride:read'])]
    private bool $isVerified = false;




    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
        $this->ridesAsDriver = new ArrayCollection();
        $this->ridesAsPassenger = new ArrayCollection();
        $this->gender = 'NO';
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
            $vehicle->setOwner($this); 
        }
        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            if ($vehicle->getOwner() === $this) {
                $vehicle->setOwner(null);
            }
        }
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


    /**
     * @return Collection<int, Ride>
     */
    public function getRidesAsDriver(): Collection
    {
        return $this->ridesAsDriver;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRidesAsPassenger(): Collection
    {
        return $this->ridesAsPassenger;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }


    public function getName(): string
    {
        return $this->pseudo ?? 'Nom inconnu';
    }



    public function getImageUrl(): string
    {
        return $this->image ? 'images/' . $this->image : 'images/Profil_Base.png';
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }


    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;
        return $this;
    }


    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }



    public function getImage(): ?string
{
    return $this->image;
}



public function getGender(): ?string
{
    return $this->gender;
}

public function setGender(string $gender): self
{
    $this->gender = $gender;
    return $this;
}

public function getGenderLabel(): string
{
    return match ($this->gender) {
        'F' => 'Femme',
        'M' => 'Homme',
        default => 'Non renseigné',
    };
}
}
