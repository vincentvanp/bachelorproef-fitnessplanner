<?php

namespace App\Controller\account\reset_password;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use App\Form\NewPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends BaseController
{
    #[Route('/account/reset/password/{token}', name: 'app_account_reset_pass')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        string $token,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $tokenEntity = $em->getRepository(TokenEntity::class)->findOneBy(['token' => $token]);
        $form = $this->createForm(NewPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $tokenEntity->getMadeBy();
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $data['password']
            ));
            $em->persist($user);
            $em->flush();
            $tokenEntity->setExpiresAt(new \DateTime('yesterday'));

            $this->addFlash('success', 'Password changed successfully.');

            return $this->redirectToRoute('app_account'); // Replace with your route
        }

        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
