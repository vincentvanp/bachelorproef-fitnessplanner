<?php

namespace App\Controller\coach;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route('/coach', name: 'app_coach')]
    public function index(): Response
    {
        return $this->render('coach/index.html.twig', [
            'clients' => $this->getUser()->getClients(),
        ]);
    }
}
