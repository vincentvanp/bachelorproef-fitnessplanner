<?php

namespace App\Controller\coach\client_management;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\training\coach\delete\VerificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveController extends BaseController
{
    #[Route('/coach/client/remove/{id}', name: 'app_coach_client_remove')]
    public function removeClient(User $client, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(VerificationType::class, ['confirm' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->removeClient($client);
            $em->flush();

            $this->addFlash('success', 'The client has been removed.');

            return $this->redirectToRoute('app_coach');
        }

        return $this->render('coach/client-management/remove/index.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
}
