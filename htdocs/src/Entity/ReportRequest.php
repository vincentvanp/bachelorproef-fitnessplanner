<?php

namespace App\Entity;

class ReportRequest
{
    public function __construct(
        private readonly string $month,
        private readonly User $user,
        private readonly string $email,
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

    public function getEmail(): string
    {
        return $this->email;
    }
}
