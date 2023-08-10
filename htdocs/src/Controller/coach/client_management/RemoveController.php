<?php

namespace App\Controller\coach\client_management;

use App\Controller\BaseController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveController extends BaseController
{
    #[Route('/coach/client/remove/confirm/{id}', name: 'app_coach_client_remove_confirm')]
    public function removeClientCheck(User $client): Response
    {
        return $this->render('coach/client-management/remove/index.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/coach/client/remove/{id}', name: 'app_coach_client_remove')]
    public function removeClient(User $client, EntityManagerInterface $em): Response
    {
        $this->getUser()->removeClient($client);
        $em->flush();

        return $this->redirectToRoute('app_coach');
    }
}
