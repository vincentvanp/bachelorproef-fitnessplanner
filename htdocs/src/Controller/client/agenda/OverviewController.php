<?php

namespace App\Controller\client\agenda;

use App\Controller\BaseController;
use App\Form\datefilter\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends BaseController
{
    #[Route('/client/agenda/overview', name: 'app_client_agenda')]
    public function clientAgenda(TrainingRepository $trainingRepository, Request $request): Response
    {
        $data = [
            'start' => new \DateTime('now 00:00'),
            'end' => new \DateTime('now + 1 week 23:59'),
        ];
        $form = $this->createForm(TrainingType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data['end']->modify('+1 day');
        }

        $trainings = $trainingRepository->findTrainings(
            start: $data['start'],
            end: $data['end'],
            clientId: $this->getUser()->getId(),
        );

        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }
}
