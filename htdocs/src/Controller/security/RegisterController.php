<?php

namespace App\Controller\security;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends BaseController
{
    #[Route('/register/{coach}/{email}', name: 'app_register')]
    public function register(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository $roleRepository,
        User $coach = null,
        string $email = null): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $entityManager = $doctrine->getManager();
        if (isset($user)) {
            // $this->addFlash('message', 'You are added as a client'); //TODO Fixen
            $user->addCoach($coach);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        $user = new User();
        if (null != $coach) {
            if ('coach' == $coach->getRole()->first()->getName()) {
                $user->addCoach($coach);
                $user->setEmail($email);
            }
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
            $user->addRole($roleRepository->find(2));

            $entityManager->persist($user);
            $entityManager->flush();
            // $this->addFlash('success', $translator->trans('user.register.success'));

            return $this->redirectToRoute('app_client');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'security' => 1,
        ]);
    }
}
