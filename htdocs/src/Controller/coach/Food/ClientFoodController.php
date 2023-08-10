<?php

namespace App\Controller\coach\Food;

use App\Controller\BaseController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientFoodController extends BaseController
{
    #[Route('/coach/client/food/{id}', name: 'app_coach_client_food')]
    public function getFoodOfClient(User $client): Response
    {
        return $this->render('coach/client-management/food/index.html.twig', [
            'foods' => $client->getFood(),
        ]);
    }
}
