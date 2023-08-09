<?php

namespace App\Controller\training\coach;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingDetailController extends BaseController
{
    #[Route('/coach/training/{training_id}/{client_id}', name: 'app_coach_training_detail')]
    public function coachTrainingDetail(
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training): Response
    {
        return $this->render('coach/client-management/training/detail/index.html.twig', [
            'client' => $client,
            'training' => $training,
        ]);
    }
}
