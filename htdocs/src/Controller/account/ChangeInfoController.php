<?php

namespace App\Controller\account;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\AccountInfoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangeInfoController extends BaseController
{
    #[Route('/account/edit', name: 'app_account_edit')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountInfoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $formData->getEmail()]);
            if ($existingUser && $existingUser !== $user) {
                $form->get('email')->addError(new FormError('This email is already in use by another user.'));

                return $this->render('account/info/edit.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/info/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
