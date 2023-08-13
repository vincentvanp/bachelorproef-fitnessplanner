<?php

namespace App\Twig\coach\training;

use App\Entity\Training;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OverviewExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('configureStatus', [$this, 'configureStatus']),
        ];
    }

    public function configureStatus(Training $training): string
    {
        if ($training->isReviewed()) {
            return 'reviewed';
        }
        if (0 == $training->getDurationActual()) {
            return 'uncompleted';
        }

        return 'unreviewed';
    }
}
