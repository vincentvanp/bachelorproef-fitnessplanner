<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
    #[Route('/coach/personal/overview', name: 'app_training')]
    public function coachPersonal(TrainingRepository $trainingRepository, Request $request): Response
    {
        $defaultData = [
            'start' => new \DateTime('now'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createFormBuilder($defaultData)
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $trainings = $trainingRepository->findCoachPersonal(
            $this->getUser()->getId(),
            $defaultData['start'],
            $defaultData['end'],
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findCoachPersonal(
                $this->getUser()->getId(),
                $data['start'],
                $data['end'],
            );
        }

        return $this->render('coach/personal/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }
}
