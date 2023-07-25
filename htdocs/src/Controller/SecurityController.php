<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\RoleRepository;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function defaultRoute(): Response
    {
        $user = $this->getUser();

        $role = $user->getRole();
        
        if(count($role) != 1) { //TODO Pagina fiksen 
            return $this->render('calendar/both/index.html.twig');
        }

        $role = $role->first();

        if($role->getName() == "coach") {
            return $this->redirectToRoute('app_coach');
        }
        return $this->redirectToRoute('app_client_calendar');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'security' => 1,
        ]);
        
    }

    #[Route('/register/{coach}/{email}', name: 'app_register')]
    public function register(
        Request $request, 
        ManagerRegistry $doctrine, 
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository $roleRepository,
        User $coach = null,
        string $email = null): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(array('email' => $email));
        $entityManager = $doctrine->getManager();
        if(isset($user)) {
            //dd('hey');
            //dd($user);
            //$this->addFlash('message', 'You are added as a client'); //TODO Fixen
            $user->addCoach($coach);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        $user = new User();
        if($coach != null ) {
            if($coach->getRole()->first()->getName() == 'coach') {
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

            return $this->redirectToRoute('app_client_calendar');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'security' => 1,
        ]);
    }

    /*#[Route('/register/client/{id}/{email}')]
    public function registerClient(User $coach, string $email): Response
    {

    }*/

}
