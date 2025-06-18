<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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

    #[ORM\Column]
    private array $roles = [];

    // #[ORM\Column]
    // private ?int $credits = 20;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'owner')]
    private Collection $Renault;

    public function __construct()
    {
        $this->Renault = new ArrayCollection();
    }

    public function getUserIdentifier(): string
{
    return $this->email;
}

public function eraseCredentials(): void
{
}

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

    // public function getCredits(): ?int
    // {
    //     return $this->credits;
    // }

    // public function setCredits(int $credits): static
    // {
    //     $this->credits = $credits;

    //     return $this;
    // }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getRenault(): Collection
    {
        return $this->Renault;
    }

    public function addRenault(Vehicle $renault): static
    {
        if (!$this->Renault->contains($renault)) {
            $this->Renault->add($renault);
            $renault->setOwner($this);
        }

        return $this;
    }

    public function removeRenault(Vehicle $renault): static
    {
        if ($this->Renault->removeElement($renault)) {
            // set the owning side to null (unless already changed)
            if ($renault->getOwner() === $this) {
                $renault->setOwner(null);
            }
        }

        return $this;
    }

    #[ORM\ManyToMany(mappedBy: 'passengers', targetEntity: Ride::class)]
    private Collection $ridesAsPassenger;



    #[ORM\Column(type: 'integer')]
    private int $credits = 20;
    
    public function getCredits(): int
    {
        return $this->credits;
    }
    
    public function setCredits(int $credits): self
    {
        $this->credits = $credits;
        return $this;
    }

}


