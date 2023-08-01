<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\CompleteTrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends BaseController
{
    public function __construct(private readonly TrainingRepository $trainingRepository)
    {
    }

    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'client' => $client,
            'trainings' => $this->trainingRepository->findClientTrainings($client->getId(),
                new \DateTime('today 0:01'),
                new \DateTime('today 23:59')),
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
    public function clientTrainingComplete(EntityManagerInterface $entityManager, Training $training, Request $request): Response
    {
        $form = $this->createForm(CompleteTrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_training_detail', ['id' => $training->getId()]);
        }

        return $this->render('client/training/review.html.twig', [
            'client' => $this->getUser(),
            'training' => $training,
            'form' => $form->createView(),
        ]);
    }
}
