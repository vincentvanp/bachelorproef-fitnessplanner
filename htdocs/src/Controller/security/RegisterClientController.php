<?php

namespace App\Controller\security;

use App\Controller\BaseController;
use App\Entity\TokenEntity;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegisterClientController extends BaseController
{
    #[Route('/register/client/{token}', name: 'app_register')]
    public function register(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        string $token = null
    ): Response {
        $tokenStorage->setToken(null);
        $tokenEntity = $entityManager->getRepository(TokenEntity::class)->findOneBy(['token' => $token]);
        if (isset($tokenEntity) && $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $tokenEntity->getEmail()])) {
            $user->addCoach($tokenEntity->getCoach());
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        $user = new User();
        $user->setUserToClient();

        if (isset($tokenEntity)) {
            if ($tokenEntity->isExspired()) {
                return $this->render('security/expired.html.twig');
            }
            // $this->addFlash('message', 'You are added as a client'); //TODO Fixen
            $user->addCoach($tokenEntity->getCoach());
            $user->setEmail($tokenEntity->getEmail());
            $tokenEntity->setExpiresAt(new \DateTime('now - 1 day'));
        }
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

            return $this->redirectToRoute('app_client');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
