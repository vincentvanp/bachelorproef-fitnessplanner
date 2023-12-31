<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use App\Form\training\coach\ReviewTrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends BaseController
{
    #[Route('/coach/training/review/{training_id}/{client_id}', name: 'app_coach_training_review')]
    public function review(
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $training->setReviewed(true);
        $form = $this->createForm(ReviewTrainingType::class, $training);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_training', ['id' => $client->getId()]);
        }

        return $this->render('training/coach/review.html.twig', [
            'client' => $client,
            'training' => $training,
            'form' => $form->createView(),
        ]);
    }
}
