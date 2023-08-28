<?php

namespace App\Controller\coach\training;

use App\Controller\BaseController;
use App\Entity\Training;
use App\Entity\User;
use App\Form\training\coach\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends BaseController
{
    #[Route('/coach/training/add/{withTrainer}/{id}', name: 'app_coach_training_add')]
    public function coachAddTraining(
        EntityManagerInterface $entityManager,
        Request $request,
        MailerInterface $mailer,
        bool $withTrainer,
        int $id = null,
    ): Response {
        $training = new Training();
        $coach = $this->getUser();
        $training->setCoach($coach);
        if (null != $id) {
            $training->addClient($entityManager->getRepository(User::class)->find($id));
        }
        $training->setWithTrainer($withTrainer);

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $training->setDurationActual(0);
            $entityManager->persist($training);
            $entityManager->flush();

            foreach ($training->getClients() as $client) {
                if ($client->getAccountSettings()->isNewTraining()) {
                    $email = (new TemplatedEmail())
                        ->from('vincent.vp@icloud.com')
                        ->to($client->getEmail())
                        ->subject('New training!')
                        ->htmlTemplate('emails/notification/new_training.html.twig')
                        ->context([
                            'coach' => $this->getUser()->getFullName(),
                            'client' => $client->getFullName(),
                        ]);

                    $mailer->send($email);
                }
            }

            if (null == $id) {
                return $this->redirectToRoute('app_coach_personal');
            }

            return $this->redirectToRoute('app_coach_training', ['id' => $id]);
        }

        return $this->render('training/coach/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
