<?php

namespace App\Controller\client\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Form\CompleteTrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompleteController extends BaseController
{
    #[Route('/client/training/complete/{id}', name: 'app_client_training_complete')]
    public function clientTrainingComplete(
        Training $training,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
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
