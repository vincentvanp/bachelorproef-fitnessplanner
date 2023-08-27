<?php

namespace App\Entity;

class Report
{
    private int $advisedTrainingTime;
    private int $effectiveTrainingTime;
    private int $dailyAverage;
    private float $totalWeightDifference;
    private float $percentageCompleted;

    public function __construct(private readonly User $user)
    {
    }

    public function setAdvisedTrainingTime(int $advisedTrainingTime): void
    {
        $this->advisedTrainingTime = $advisedTrainingTime;
    }

    public function setEffectiveTrainingTime(int $effectiveTrainingTime): void
    {
        $this->effectiveTrainingTime = $effectiveTrainingTime;
        $this->calculatePercentageCompleted();
    }

    public function setDailyAverage(int $dailyAverage): void
    {
        $this->dailyAverage = $dailyAverage;
    }

    public function setTotalWeightDifference(float $totalWeightDifference): void
    {
        $this->totalWeightDifference = $totalWeightDifference;
    }

    public function calculatePercentageCompleted(): void
    {
        if ($this->advisedTrainingTime > 0) {
            $this->percentageCompleted = ($this->effectiveTrainingTime / $this->advisedTrainingTime) * 100;
        } else {
            $this->percentageCompleted = 0;
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAdvisedTrainingTime(): int
    {
        return $this->advisedTrainingTime;
    }

    public function getEffectiveTrainingTime(): int
    {
        return $this->effectiveTrainingTime;
    }

    public function getPercentageCompleted(): float
    {
        return $this->percentageCompleted;
    }

    public function getDailyAverage(): int
    {
        return $this->dailyAverage;
    }

    public function getTotalWeightDifference(): float
    {
        return $this->totalWeightDifference;
    }
}
