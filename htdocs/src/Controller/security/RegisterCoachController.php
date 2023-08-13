<?php

namespace App\Controller\security;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterCoachController extends BaseController
{
    #[Route('/register/coach', name: 'app_register_coach')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $user->setUserToCoach();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                // $this->addFlash('error', $translator->trans('user.register.failed'));

                return $this->render('security/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            ));

            $entityManager->persist($user);
            $entityManager->flush();
            // $this->addFlash('success', $translator->trans('user.register.success'));

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'security' => 1,
        ]);
    }
}
