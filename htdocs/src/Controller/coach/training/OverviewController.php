<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\datefilter\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends BaseController
{
    #[Route('/coach/training/{id}', name: 'app_coach_training')]
    public function coachClientOverview(TrainingRepository $trainingRepository, Request $request, User $client): Response
    {
        $data = [
            'start' => new \DateTime('now - 1 week'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createForm(TrainingType::class, $data);

        $trainings = $trainingRepository->findTrainings(
            start: $data['start'],
            end: $data['end'],
            clientId: $client->getId(),
            coachId: $this->getUser()->getId(),
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findTrainings(
                start: $data['start'],
                end: $data['end'],
                clientId: $client->getId(),
                coachId: $this->getUser()->getId(),
            );
        }

        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
            'client' => $client,
        ]);
    }
}
