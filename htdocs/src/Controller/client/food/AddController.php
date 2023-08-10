<?php

namespace App\Controller\client\food;

use App\Controller\BaseController;
use App\Entity\Food;
use App\Form\FoodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends BaseController
{
    #[Route('/food/add', name: 'app_food_add')]
    public function addFood(Request $request, EntityManagerInterface $em): Response
    {
        $food = new Food();
        $food->setUser($this->getUser());

        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFood = $form->getData();
            $em->persist($newFood);
            $em->flush();

            return $this->redirectToRoute('app_coach_client_food');
        }

        return $this->render('client/food/add.html.twig', [
            'form' => $form,
        ]);
    }
}
