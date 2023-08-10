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
        $user = $this->getUser();

        $role = $user->getRole();

        if (1 != count($role)) { // TODO Pagina fiksen
            return $this->render('calendar/both/index.html.twig');
        }

        $role = $role->first();

        if ('coach' == $role->getName()) {
            return $this->redirectToRoute('app_coach');
        }

        return $this->redirectToRoute('app_client');
    }
}
