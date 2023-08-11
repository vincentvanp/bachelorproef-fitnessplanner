<?php

namespace App\Controller\client\food;

use App\Controller\BaseController;
use App\Entity\Food;
use App\Form\FoodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends BaseController
{
    #[Route('/client/food/edit/{id}', name: 'app_food_edit')]
    public function editFood(Request $request, Food $food, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFood = $form->getData();
            $em->persist($newFood);
            $em->flush();

            return $this->redirectToRoute('app_food_detail', ['id' => $food->getId()]);
        }

        return $this->render('client/food/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
