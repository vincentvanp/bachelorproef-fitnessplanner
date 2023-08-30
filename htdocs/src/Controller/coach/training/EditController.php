<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use App\Form\training\coach\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends BaseController
{
    #[Route('/coach/training/edit/{client_id}/{training_id}', name: 'app_coach_training_edit')]
    public function coachEditTraining(
        EntityManagerInterface $entityManager,
        Request $request,
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_training_detail', ['client_id' => $client->getId(), 'training_id' => $training->getId()]);
        }

        return $this->render('training/coach/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
