<?php

namespace App\Controller\security;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    #[Route('/', name: 'app_default')]
    public function defaultRoute(): Response
    {
        if (in_array('ROLE_COACH', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_coach');
        }

        return $this->redirectToRoute('app_client');
    }
}
