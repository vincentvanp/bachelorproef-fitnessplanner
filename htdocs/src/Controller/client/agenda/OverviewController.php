<?php

namespace App\Controller\client\agenda;

use App\Controller\BaseController;
use App\Repository\TrainingRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends BaseController
{
    #[Route('/client/agenda/overview', name: 'app_client_agenda')]
    public function clientAgenda(TrainingRepository $trainingRepository, Request $request): Response
    {
        $data = [
            'start' => new \DateTime('now'),
            'end' => new \DateTime('now + 1 week'),
        ];
        $form = $this->createFormBuilder($data)
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $trainings = $trainingRepository->findTrainings(
            start: $data['start'],
            end: $data['end'],
            clientId: $this->getUser()->getId(),
        );

        return $this->render('client/calendar/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }
}