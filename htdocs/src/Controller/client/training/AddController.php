<?php

namespace App\Controller\client\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Form\ClientTrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends BaseController
{
    #[Route('/client/agenda/add', name: 'app_client_agenda_add')]
    public function clientAddTraining(EntityManagerInterface $entityManager, Request $request): Response
    {
        $training = new Training();
        $training->addClient($this->getUser());

        $form = $this->createForm(ClientTrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $training->setDurationProposed($training->getDurationActual());
            $training->setWithTrainer(false);
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_agenda');
        }

        return $this->render('client/training/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
