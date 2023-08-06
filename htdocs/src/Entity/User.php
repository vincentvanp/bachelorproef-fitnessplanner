<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isTrainer = false;

    #[ORM\OneToMany(mappedBy: 'coach', targetEntity: Training::class)]
    private Collection $givenTrainings;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $role;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'clients')]
    #[ORM\JoinTable(name: 'client_coach')]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id')]
    private Collection $coaches;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'coaches')]
    #[ORM\JoinTable(name: 'client_coach')]
    #[ORM\JoinColumn(name: 'coach_id', referencedColumnName: 'id')]
    private Collection $clients;

    #[ORM\ManyToMany(targetEntity: Training::class, mappedBy: 'clients')]
    private Collection $trainings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Food::class)]
    private Collection $food;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Weight::class)]
    private Collection $weights;

    public function __construct()
    {
        $this->givenTrainings = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->coaches = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->food = new ArrayCollection();
        $this->weights = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function isIsTrainer(): ?bool
    {
        return $this->isTrainer;
    }

    public function setIsTrainer(bool $isTrainer): static
    {
        $this->isTrainer = $isTrainer;

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getGivenTrainings(): Collection
    {
        return $this->givenTrainings;
    }

    public function addGivenTrainings(Training $training): self
    {
        if (!$this->givenTrainings->contains($training)) {
            $this->givenTrainings->add($training);
            $training->setCoach($this);
        }

        return $this;
    }

    public function removeGivenTraining(Training $training): self
    {
        if ($this->givenTrainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getCoach() === $this) {
                $training->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): static
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCoaches(): Collection
    {
        return $this->coaches;
    }

    public function addCoach(self $coach): static
    {
        if (!$this->coaches->contains($coach)) {
            $this->coaches->add($coach);
        }

        return $this;
    }

    public function removeCoach(self $coach): static
    {
        $this->coaches->removeElement($coach);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(self $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->addCoach($this);
        }

        return $this;
    }

    public function removeClient(self $client): static
    {
        if ($this->clients->removeElement($client)) {
            $client->removeCoach($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->addClient($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): static
    {
        if ($this->trainings->removeElement($training)) {
            $training->removeClient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): static
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
            $food->setUser($this);
        }

        return $this;
    }

    public function removeFood(Food $food): static
    {
        if ($this->food->removeElement($food)) {
            // set the owning side to null (unless already changed)
            if ($food->getUser() === $this) {
                $food->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Weight>
     */
    public function getWeights(): Collection
    {
        return $this->weights;
    }

    public function addWeight(Weight $weight): static
    {
        if (!$this->weights->contains($weight)) {
            $this->weights->add($weight);
            $weight->setUser($this);
        }

        return $this;
    }

    public function removeWeight(Weight $weight): static
    {
        if ($this->weights->removeElement($weight)) {
            // set the owning side to null (unless already changed)
            if ($weight->getUser() === $this) {
                $weight->setUser(null);
            }
        }

        return $this;
    }
}
