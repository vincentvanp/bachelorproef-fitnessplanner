<?php

namespace App\Twig\report;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ReportExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('calculateMinutes', [$this, 'calculateMinutes']),
        ];
    }

    public function calculateMinutes(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $sentence = '';

        if ($hours > 0) {
            $sentence .= $hours.' hour';
            if ($hours > 1) {
                $sentence .= 's';
            }
        }

        if ($remainingMinutes > 0) {
            if ($hours > 0) {
                $sentence .= ' ';
            }
            $sentence .= $remainingMinutes.' minute';
            if ($remainingMinutes > 1) {
                $sentence .= 's';
            }
        }

        return $sentence;
    }
}
