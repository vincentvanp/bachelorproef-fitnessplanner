<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CoachController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/coach', name: 'app_coach')]
    public function index(): Response
    {
        return $this->render('coach/index.html.twig', [
            'clients' => $this->getUser()->getClients(),
        ]);
    }

    #[Route('/coach/{id}/remove/confirm', name: 'app_coach_client_remove_confirm')]
    public function removeClientCheck(User $client): Response
    {
        return $this->render('coach/client-management/remove/index.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/coach/{id}/remove', name: 'app_coach_client_remove')]
    public function removeClient(User $client): Response
    {
        $this->getUser()->removeClient($client);
        $this->em->flush();

        return $this->redirectToRoute('app_coach');
    }
}
