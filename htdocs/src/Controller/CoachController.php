<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\User;
use App\Form\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CoachController extends BaseController
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly MailerInterface $mailer)
    {
    }

    #[Route('/coach', name: 'app_coach')]
    public function index(): Response
    {
        return $this->render('coach/index.html.twig', [
            'clients' => $this->getUser()->getClients(),
        ]);
    }

    #[Route('/coach/add/client', name: 'app_coach_client_add')]
    public function addClient(Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from('vincent.vp@icloud.com')
                ->to($data['email'])
                ->subject('Time for Symfony Mailer!')
                ->htmlTemplate('emails/signup.html.twig')
                ->context([
                    'coach' => $this->getUser(),
                    'adress' => $data['email'],
                ]);

            $this->mailer->send($email);

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('coach/client-management/add/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/coach/{id}/remove/confirm', name: 'app_coach_client_remove_confirm')]
    public function removeClientCheck(User $client): Response
    {
        return $this->render('coach/client-management/remove/index.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/coach/{id}/remove', name: 'app_coach_client_remove')]
    public function removeClient(User $client): Response
    {
        $this->getUser()->removeClient($client);
        $this->em->flush();

        return $this->redirectToRoute('app_coach');
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
            // dd($training);
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('coach/personal/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
