<?php

namespace App\Controller\security;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegisterCoachController extends BaseController
{
    #[Route('/register/coach/{token}', name: 'app_register_coach')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        string $token
    ): Response {
        $tokenStorage->setToken(null);
        $tokenEntity = $entityManager->getRepository(TokenEntity::class)->findOneBy(['token' => $token]);
        if (!$tokenEntity) {
            $this->addFlash('error', 'You cant register as a coach trough this token. Please request a new invite.');

            return $this->redirectToRoute('app_register');
        }
        $user = new User();
        $user->setUserToCoach();
        $user->setEmail($tokenEntity->getEmail());
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
            $tokenEntity->setExpiresAt(new \DateTime('now - 1 day'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
