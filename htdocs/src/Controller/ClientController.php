<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\ClientTrainingType;
use App\Form\CompleteTrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends BaseController
{
    public function __construct(
        private readonly TrainingRepository $trainingRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'client' => $client,
            'trainings' => $this->trainingRepository->findTrainings(
                start: new \DateTime('today 0:01'),
                end: new \DateTime('today 23:59'),
                clientId: $client->getId(),
            ),
        ]);
    }

    #[Route('/client/training/{id}', name: 'app_client_training_detail')]
    public function clientTrainingDetail(Training $training): Response
    {
        return $this->render('client/training/detail.html.twig', [
            'client' => $this->getUser(),
            'training' => $training,
        ]);
    }

    #[Route('/client/training/{id}/complete', name: 'app_client_training_complete')]
    public function clientTrainingComplete(Training $training, Request $request): Response
    {
        $form = $this->createForm(CompleteTrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $this->entityManager->persist($training);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_training_detail', ['id' => $training->getId()]);
        }

        return $this->render('client/training/review.html.twig', [
            'client' => $this->getUser(),
            'training' => $training,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/agenda/overview', name: 'app_client_agenda')]
    public function clientAgenda(TrainingRepository $trainingRepository, Request $request): Response
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

        $trainings = $trainingRepository->findTrainings(
            start: $defaultData['start'],
            end: $defaultData['end'],
            clientId: $this->getUser()->getId(),
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findTrainings(
                start: $data['start'],
                end: $data['end'],
                clientId: $this->getUser()->getId(),
            );
        }

        return $this->render('client/calendar/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }

    #[Route('/client/agenda/add', name: 'app_client_agenda_add')]
    public function clientAddTraining(TrainingRepository $trainingRepository, Request $request): Response
    {
        $training = new Training();
        $training->addClient($this->getUser());

        $form = $this->createForm(ClientTrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $training->setDurationProposed($training->getDurationActual());
            $training->setWithTrainer(false);
            $this->entityManager->persist($training);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_agenda');
        }

        return $this->render('client/training/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
