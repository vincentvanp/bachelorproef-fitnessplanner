<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Form\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddPTSessionController extends BaseController
{
    #[Route('/coach/personal/session/add', name: 'app_coach_personal_add')]
    public function addPersonalSession(EntityManagerInterface $entityManager, Request $request): Response
    {
        $training = new Training();
        $coach = $this->getUser();
        $training->setCoach($coach);

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$email = (new TemplatedEmail()) //TODO Email invites
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
            $training->setWithTrainer(true);
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('coach/personal/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
