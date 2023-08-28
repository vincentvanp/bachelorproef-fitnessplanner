<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use App\Form\delete_verification\VerificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends BaseController
{
    #[Route('/coach/training/delete/{training_id}/{client_id}', name: 'app_coach_training_delete')]
    public function coachDeleteTrainingCheck(
        Request $request,
        EntityManagerInterface $em,
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training
    ): Response {
        $form = $this->createForm(VerificationType::class, ['confirm' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($training);
            $em->flush();

            $this->addFlash('success', 'The training has been deleted.');

            return $this->redirectToRoute('app_coach_training', ['id' => $client->getId()]);
        }

        return $this->render('training/coach/delete.html.twig', [
            'training' => $training,
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
}
