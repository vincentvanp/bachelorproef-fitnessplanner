<?php

namespace App\Entity;

use Symfony\UX\Chartjs\Model\Chart;

class Report
{
    private int $percentageCompleted;

    public function __construct(
        private readonly User $User,
        private readonly int $advisedTrainingTime,
        private readonly int $effectiveTrainingTime,
        private readonly int $dailyAverage,
        private readonly float $totalWeightDifference,
        private readonly float $weightDiffLastMonth,
        private readonly Chart $weightChart,
    ) {
        $this->percentageCompleted = ($this->effectiveTrainingTime / $this->advisedTrainingTime) * 100; // TODO: Checken dat dit niet altijd 0 is
    }

    public function getUser(): User
    {
        return $this->User;
    }

    public function getAdvisedTrainingTime(): int
    {
        return $this->advisedTrainingTime;
    }

    public function getEffectiveTrainingTime(): int
    {
        return $this->effectiveTrainingTime;
    }

    public function getPercentageCompleted(): int
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

    public function getWeightDiffLastMonth(): float
    {
        return $this->weightDiffLastMonth;
    }

    public function getWeightChart(): Chart
    {
        return $this->weightChart;
    }
}
