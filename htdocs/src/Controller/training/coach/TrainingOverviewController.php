<?php

namespace App\Controller\training\coach;

use App\Controller\BaseController;
use App\Entity\User;
use App\Repository\TrainingRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingOverviewController extends BaseController
{
    #[Route('/coach/{id}/training', name: 'app_coach_training')]
    public function coachClientOverview(TrainingRepository $trainingRepository, Request $request, User $client): Response
    {
        $defaultData = [
            'start' => new \DateTime('now - 1 week'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createFormBuilder($defaultData)
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $trainings = $trainingRepository->findTrainings(
            start: $defaultData['start'],
            end: $defaultData['end'],
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

        return $this->render('coach/client-management/training/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
            'client' => $client,
        ]);
    }
}
