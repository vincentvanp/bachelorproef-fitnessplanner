<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientCalendarController extends AbstractController
{
    #[Route('/client/calendar', name: 'app_client_calendar')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'trainings' => $this->getUser()->getReceivedTrainings()->toArray(),
        ]);
    }
}
