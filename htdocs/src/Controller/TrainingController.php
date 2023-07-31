<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\User;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends BaseController
{
    #[Route('/coach/personal/overview', name: 'app_coach_personal')]
    public function coachPersonal(TrainingRepository $trainingRepository, Request $request): Response
    {
        $defaultData = [
            'start' => new \DateTime('now'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createFormBuilder($defaultData)
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $trainings = $trainingRepository->findCoachPersonal(
            $this->getUser()->getId(),
            $defaultData['start'],
            $defaultData['end'],
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findCoachPersonal(
                $this->getUser()->getId(),
                $data['start'],
                $data['end'],
            );
        }

        return $this->render('coach/personal/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
        ]);
    }

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

    #[Route('/coach/{id}/training', name: 'app_coach_training')]
    public function coachClientOverview(TrainingRepository $trainingRepository, Request $request, User $client): Response
    {
        $defaultData = [
            'start' => new \DateTime('now - 1 week'),
            'end' => new \DateTime('now + 1 week'),
        ];

        $form = $this->createFormBuilder($defaultData)
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $trainings = $trainingRepository->findClientTrainings(
            $client->getId(),
            $defaultData['start'],
            $defaultData['end'],
            $this->getUser()->getId(),
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trainings = $trainingRepository->findClientTrainings(
                $client->getId(),
                $data['start'],
                $data['end'],
                $this->getUser()->getId(),
            );
        }

        return $this->render('coach/client-management/training/index.html.twig', [
            'trainings' => $trainings,
            'dateFilter' => $form->createView(),
            'client' => $client,
        ]);
    }

    #[Route('/coach/{id}/training/add', name: 'app_coach_training_add')]
    public function coachAddTraining(EntityManagerInterface $entityManager, Request $request, User $client): Response
    {
        $training = new Training();
        $coach = $this->getUser();
        $training->setCoach($coach);
        $training->addClient($client);
        // dd($training);

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

    #[Route('/coach/{client_id}/training/{training_id}', name: 'app_coach_training_detail')]
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

    #[Route('/coach/{client_id}/training/{training_id}/edit', name: 'app_coach_training_edit')]
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
            $training->setDurationActual(0);
            $training->setWithTrainer(false);
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_training_detail', ['client_id' => $client->getId(), 'training_id' => $training->getId()]);
        }

        return $this->render('coach/client-management/training/edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/coach/{client_id}/training/{training_id}/delete/check', name: 'app_coach_training_delete_check')]
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

    #[Route('/coach/{client_id}/training/{training_id}/delete', name: 'app_coach_training_delete')]
    public function coachDeleteTraining(
        #[MapEntity(expr: 'repository.find(client_id)')]
        User $client,
        #[MapEntity(expr: 'repository.find(training_id)')]
        Training $training,
        EntityManagerInterface $em): Response
    {
        // $this->getUser()->removeClient($client);
        $em->remove($training);
        $em->flush();

        return $this->redirectToRoute('app_coach_training', ['id' => $client->getId()]);
    }
}
