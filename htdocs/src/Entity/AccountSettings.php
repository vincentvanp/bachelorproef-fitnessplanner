<?php

namespace App\Entity;

use App\Repository\AccountSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountSettingsRepository::class)]
class AccountSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $ptUpdates = true;

    #[ORM\Column]
    private ?bool $newTraining = true;

    #[ORM\Column]
    private ?bool $newReview = true;

    #[ORM\Column]
    private ?bool $monthlyProgress = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPtUpdates(): ?bool
    {
        return $this->ptUpdates;
    }

    public function setPtUpdates(bool $ptUpdates): static
    {
        $this->ptUpdates = $ptUpdates;

        return $this;
    }

    public function isNewTraining(): ?bool
    {
        return $this->newTraining;
    }

    public function setNewTraining(bool $newTraining): static
    {
        $this->newTraining = $newTraining;

        return $this;
    }

    public function isNewReview(): ?bool
    {
        return $this->newReview;
    }

    public function setNewReview(bool $newReview): static
    {
        $this->newReview = $newReview;

        return $this;
    }

    public function isMonthlyProgress(): ?bool
    {
        return $this->monthlyProgress;
    }

    public function setMonthlyProgress(bool $monthlyProgress): static
    {
        $this->monthlyProgress = $monthlyProgress;

        return $this;
    }
}
