<?php

namespace App\Controller\training\coach;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use App\Form\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddTrainingController extends BaseController
{
    #[Route('/coach/training/add/{id}', name: 'app_coach_training_add')]
    public function coachAddTraining(
        EntityManagerInterface $entityManager,
        Request $request,
        User $client): Response
    {
        $training = new Training();
        $coach = $this->getUser();
        $training->setCoach($coach);
        $training->addClient($client);

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$email = (new TemplatedEmail()) //TODO Email notifications
                ->from('vincent.vp@icloud.com')
                ->to($data['email'])
                ->subject('Time for Symfony Mailer!')
                ->htmlTemplate('emails/signup.html.twig')
                ->context([
                    'coach' => $this->getUser(),
                    'adress' => $data['email'],
                ]);*/

            // $this->mailer->send($email);

            $training = $form->getData();
            $training->setDurationActual(0);
            $training->setWithTrainer(false);
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_training', ['id' => $client->getId()]);
        }

        return $this->render('coach/client-management/training/add/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
