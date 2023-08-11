<?php

namespace App\Controller\coach\client_management;

use App\Controller\BaseController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends BaseController
{
    public function __construct(private readonly MailerInterface $mailer)
    {
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
            $this->sendEmail($form->getData());

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('coach/client-management/add/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array<string, string> $data
     */
    private function sendEmail(array $data): void
    {
        $email = (new TemplatedEmail())
            ->from('vincent.vp@icloud.com')
            ->to($data['email'])
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'coach' => $this->getUser(),
                'address' => $data['email'],
                'message' => $data['message'],
            ]);

        $this->mailer->send($email);
    }
}
