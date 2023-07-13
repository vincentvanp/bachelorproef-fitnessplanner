<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
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
    private ?string $commentCoach = null;

    #[ORM\Column(length: 1000)]
    private ?string $commentClient = null;

    #[ORM\ManyToOne(inversedBy: 'receivedTrainings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private User $client;

    #[ORM\ManyToOne(inversedBy: 'givenTrainings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private User $coach;

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

    public function setCommentCoach(string $commentCoach): static
    {
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

    public function getClient(): User
    {
        return $this->client;
    }

    public function setClient(User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCoach(): User
    {
        return $this->coach;
    }

    public function setCoach(User $coach): self
    {
        $this->coach = $coach;

        return $this;
    }
}
