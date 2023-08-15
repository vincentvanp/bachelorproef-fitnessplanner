<?php

namespace App\Controller\account;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends BaseController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/detail.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
