<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $durationProposed = null;

    #[ORM\Column]
    private ?int $durationActual = null;

    #[ORM\Column(length: 1000)]
    private ?string $commentCoach = '';

    #[ORM\Column(length: 1000)]
    private ?string $commentClient = '';

    #[ORM\ManyToOne(inversedBy: 'givenTrainings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $coach;

    #[ORM\Column]
    private ?bool $withTrainer = false;

    #[ORM\Column]
    private ?bool $reviewed = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'trainings')]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDurationProposed(): ?int
    {
        return $this->durationProposed;
    }

    public function setDurationProposed(int $durationProposed): static
    {
        $this->durationProposed = $durationProposed;

        return $this;
    }

    public function getDurationActual(): ?int
    {
        return $this->durationActual;
    }

    public function setDurationActual(int $durationActual): static
    {
        $this->durationActual = $durationActual;

        return $this;
    }

    public function getCommentCoach(): ?string
    {
        return $this->commentCoach;
    }

    public function setCommentCoach(?string $commentCoach): static
    {
        if (!$commentCoach) {
            $commentCoach = '';
        }
        $this->commentCoach = $commentCoach;

        return $this;
    }

    public function getCommentClient(): ?string
    {
        return $this->commentClient;
    }

    public function setCommentClient(string $commentClient): static
    {
        $this->commentClient = $commentClient;

        return $this;
    }

    public function getCoach(): User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): self
    {
        $this->coach = $coach;

        return $this;
    }

    public function isWithTrainer(): ?bool
    {
        return $this->withTrainer;
    }

    public function setWithTrainer(bool $withTrainer): static
    {
        $this->withTrainer = $withTrainer;

        return $this;
    }

    public function isReviewed(): ?bool
    {
        return $this->reviewed;
    }

    public function setReviewed(bool $reviewed): static
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(User $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }

        return $this;
    }

    public function removeClient(User $client): static
    {
        $this->clients->removeElement($client);

        return $this;
    }
}
