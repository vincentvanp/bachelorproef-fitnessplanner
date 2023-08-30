<?php

namespace App\Controller\coach\client_management;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use App\Form\client_management\InviteClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends BaseController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/coach/add/client', name: 'app_coach_client_add')]
    public function addClient(Request $request): Response
    {
        $form = $this->createForm(InviteClientType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tokenEntity = $this->createToken($data);
            $data['token'] = $tokenEntity->getToken();
            $this->sendEmail($data);

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
                'token' => $data['token'],
                'message' => $data['message'],
            ]);

        $this->mailer->send($email);
    }

    /**
     * @param array<string, string> $data
     */
    private function createToken(array $data): TokenEntity
    {
        $tokenEntity = new TokenEntity();
        $tokenEntity->setToken(bin2hex(random_bytes(32)));
        $tokenEntity->setEmail($data['email']);
        $tokenEntity->setCoach($this->getUser());
        $tokenEntity->setExpiresAt(new \DateTime('now + 1 week'));
        $this->entityManager->persist($tokenEntity);
        $this->entityManager->flush();

        return $tokenEntity;
    }
}
