<?php

namespace App\Controller\client\agenda;

use App\Controller\BaseController;
use App\Repository\TrainingRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('end', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm text-white'],
            ])
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
            'dateFilter' => $form,
        ]);
    }
}
