<?php

namespace App\Controller\client;

use App\Controller\BaseController;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route('/client', name: 'app_client')]
    public function index(TrainingRepository $trainingRepository): Response
    {
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'client' => $client,
            'trainings' => $trainingRepository->findTrainings(
                start: new \DateTime('today 0:01'),
                end: new \DateTime('today 23:59'),
                clientId: $client->getId(),
            ),
        ]);
    }
}
