<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Form\datefilter\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonalAgendaController extends BaseController
{
    #[Route('/coach/personal/overview', name: 'app_coach_personal')]
    public function coachPersonal(TrainingRepository $trainingRepository, Request $request): Response
    {
        $defaultData = [
            'start' => new \DateTime('now'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createForm(TrainingType::class, $defaultData);

        $trainings = $trainingRepository->findTrainings(
            start: $defaultData['start'],
            end: $defaultData['end'],
            coachId: $this->getUser()->getId(),
            isWithTrainer: true,
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findTrainings(
                start: $data['start'],
                end: $data['end'],
                coachId: $this->getUser()->getId(),
                isWithTrainer: true,
            );
        }

        return $this->render('coach/personal/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }
}
