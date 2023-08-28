<?php

namespace App\Controller\client\food;

use App\Controller\BaseController;
use App\Form\datefilter\FoodType;
use App\Repository\FoodRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route('/client/food', name: 'app_client_food')]
    public function clientFood(FoodRepository $foodRepository, Request $request): Response
    {
        $data = ['date' => new \DateTime('now')];
        $form = $this->createForm(FoodType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $food = $foodRepository->findBy([
            'date' => $data['date'],
            'user' => $this->getUser(),
        ]);

        return $this->render('food/index.html.twig', [
            'foods' => $food,
            'dateFilter' => $form->createView(),
        ]);
    }
}
