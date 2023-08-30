<?php

namespace App\Controller\coach\Food;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\datefilter\FoodType;
use App\Repository\FoodRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientFoodController extends BaseController
{
    #[Route('/coach/client/food/{id}', name: 'app_coach_client_food')]
    public function getFoodOfClient(User $client, FoodRepository $foodRepository, Request $request): Response
    {
        $data = ['date' => new \DateTime('now')];
        $form = $this->createForm(FoodType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $food = $foodRepository->findBy([
            'date' => $data['date'],
            'user' => $client,
        ]);

        return $this->render('food/index.html.twig', [
            'dateFilter' => $form->createView(),
            'foods' => $food,
            'client' => $client,
        ]);
    }
}
