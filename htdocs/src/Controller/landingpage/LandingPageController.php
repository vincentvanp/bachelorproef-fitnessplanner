<?php

namespace App\Controller\landingpage;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LandingPageController extends BaseController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/home', name: 'app_landing_page')]
    public function index(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        $tokenStorage->setToken(null);
        $defaultData = ['Email' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class, [
                'label' => false,
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Invite me',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tokenEntity = $this->createToken($data);
            $data['token'] = $tokenEntity->getToken();
            $this->sendEmail($data);
            $this->addFlash('success', 'Thr email is sent');

            return $this->redirectToRoute('app_landing_page');
        }

        return $this->render('landingpage/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array<string, string> $data
     */
    private function createToken(array $data): TokenEntity
    {
        $tokenEntity = new TokenEntity();
        $tokenEntity->setToken(bin2hex(random_bytes(32)));
        $tokenEntity->setEmail($data['email']);
        $tokenEntity->setExpiresAt(new \DateTime('now + 1 day'));
        $this->entityManager->persist($tokenEntity);
        $this->entityManager->flush();

        return $tokenEntity;
    }

    /**
     * @param array<string, string> $data
     */
    private function sendEmail(array $data): void
    {
        $email = (new TemplatedEmail())
            ->from('vincent.vp@icloud.com')
            ->to($data['email'])
            ->subject('register now!')
            ->htmlTemplate('emails/coach/signup.html.twig')
            ->context([
                'token' => $data['token'],
            ]);

        $this->mailer->send($email);
    }
}
