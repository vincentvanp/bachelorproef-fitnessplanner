<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CoachController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly MailerInterface $mailer){}

    #[Route('/coach', name: 'app_coach')]
    public function index(): Response
    {
        return $this->render('coach/index.html.twig', [
            'clients' => $this->getUser()->getClients(),
        ]);
    }

    #[Route(name: 'app_coach_client_add')]
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
            
            $email = (new Email())
                ->from('vincent.vp@icloud.com')
                ->to($data['email'])
                ->subject('Time for Symfony Mailer!')
                ->text($data['message']);

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
}
