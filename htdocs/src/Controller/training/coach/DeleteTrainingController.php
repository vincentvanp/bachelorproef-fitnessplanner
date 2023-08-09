<?php

namespace App\Controller\training\coach;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrainingController extends BaseController
{
    #[Route('/coach/training/delete/check/{client_id}/{training_id}', name: 'app_coach_training_delete_check')]
    public function coachDeleteTrainingCheck(
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training): Response
    {
        return $this->render('coach/client-management/training/delete/check.html.twig', [
            'client' => $client,
            'training' => $training,
        ]);
    }

    #[Route('/coach/training/delete/{client_id}/{training_id}', name: 'app_coach_training_delete')]
    public function coachDeleteTraining(
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training,
        EntityManagerInterface $em): Response
    {
        $em->remove($training);
        $em->flush();

        return $this->redirectToRoute('app_coach_training', ['id' => $client->getId()]);
    }
}
