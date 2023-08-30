<?php

namespace App\Controller\client\training;

use App\Controller\BaseController;
use App\Entity\Training;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends BaseController
{
    #[Route('/client/training/{id}', name: 'app_client_training_detail')]
    public function clientTrainingDetail(Training $training): Response
    {
        return $this->render('training/detail.html.twig', [
            'client' => $this->getUser(),
            'training' => $training,
        ]);
    }
}
