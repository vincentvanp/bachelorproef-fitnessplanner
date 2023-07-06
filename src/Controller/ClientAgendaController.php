<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAgendaController extends AbstractController
{
    #[Route('/client/agenda', name: 'app_client_agenda')]
    public function index(): Response
    {
        return $this->render('client_agenda/index.html.twig', [
            'trainings' => $this->getUser()->getReceivedTrainings()->toArray(),
        ]);
    }
}
