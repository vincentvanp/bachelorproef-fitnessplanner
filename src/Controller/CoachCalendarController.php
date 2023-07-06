<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachCalendarController extends AbstractController
{
    #[Route('/coach/calendar', name: 'app_coach_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/coach/index.html.twig', [
            'trainings' => $this->getUser()->getGivenTrainings()->toArray(),
        ]);
    }
}
