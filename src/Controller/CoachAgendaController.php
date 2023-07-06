<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachAgendaController extends AbstractController
{
    #[Route('/coach/agenda', name: 'app_coach_agenda')]
    public function index(): Response
    {
        return $this->render('client_agenda/index.html.twig', [
            'trainings' => $this->getUser()->getGivenTrainings()->toArray(),
        ]);
    }
}
