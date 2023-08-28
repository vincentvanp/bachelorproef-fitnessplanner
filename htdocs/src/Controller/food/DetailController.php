<?php

namespace App\Controller\food;

use App\Controller\BaseController;
use App\Entity\Food;
use App\Form\food\FoodReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends BaseController
{
    #[Route('/food/detail/{id}', name: 'app_food_detail')]
    public function foodDetail(Food $food, Request $request, EntityManagerInterface $em): Response
    {
        if (in_array('ROLE_CLIENT', $this->getUser()->getRoles())) {
            return $this->render('food/detail.html.twig', [
                'food' => $food,
            ]);
        }

        $form = $this->createForm(FoodReviewType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food = $form->getData();
            $em->persist($food);
            $em->flush();

            return $this->redirectToRoute('app_coach_client_food', ['id' => $food->getUser()->getId()]);
        }

        return $this->render('food/detail.html.twig', [
            'food' => $food,
            'form' => $form->createView(),
        ]);
    }
}
