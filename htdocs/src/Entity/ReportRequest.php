<?php

namespace App\Entity;

class ReportRequest
{
    public function __construct(
        private readonly string $month,
        private readonly User $user,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMonth(): string
    {
        return $this->month;
    }
}
