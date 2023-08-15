<?php

namespace App\Controller\account\reset_password;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetEmailController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    #[Route('/account/request/reset/password', name: 'app_account_reset_request')]
    public function index(): Response
    {
        $this->sendEmail($this->createToken($this->getUser()->getEmail()));

        $this->addFlash('success', 'Password reset email sent.');

        return $this->redirectToRoute('app_account');
    }

    /**
     * @throws \Exception
     */
    private function createToken(string $email): TokenEntity
    {
        $tokenEntity = new TokenEntity();
        $tokenEntity->setToken(bin2hex(random_bytes(32)));
        $tokenEntity->setEmail($email);
        $tokenEntity->setMadeBy($this->getUser());
        $tokenEntity->setExpiresAt(new \DateTime('now + 1 hour'));
        $this->entityManager->persist($tokenEntity);
        $this->entityManager->flush();

        return $tokenEntity;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendEmail(TokenEntity $tokenEntity): void
    {
        $email = (new TemplatedEmail())
            ->from('vincent.vp@icloud.com')
            ->to($tokenEntity->getEmail())
            ->subject('Reset password')
            ->htmlTemplate('emails/reset-password.html.twig')
            ->context([
                'token' => $tokenEntity->getToken(),
            ]);

        $this->mailer->send($email);
    }
}
