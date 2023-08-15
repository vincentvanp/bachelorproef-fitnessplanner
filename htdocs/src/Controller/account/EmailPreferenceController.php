<?php

namespace App\Controller\account;

use App\Controller\BaseController;
use App\Form\AccountSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailPreferenceController extends BaseController
{
    #[Route('/account/email/preference', name: 'app_account_email_pref')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountSettingsType::class, $user->getAccountSettings());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newSettings = $form->getData();
            $em->persist($newSettings);
            $em->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/email/preferences.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
