<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FoodController extends BaseController
{
    public function __construct()
    {
    }

    public function getAllFood(User $user = null): Collection
    {
        if (null == $user) {
            $user = $this->getUser();
        }

        return $user->getFood();
    }

    #[Route('/coach/client/{id}/food', name: 'app_coach_client_food')]
    public function getFoodOfClient(User $client): Response
    {
        return $this->render('coach/client-management/food/index.html.twig', [
            'foods' => $this->getAllFood($client),
        ]);
    }
}
