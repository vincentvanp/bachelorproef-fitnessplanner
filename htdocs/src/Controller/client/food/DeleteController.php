<?php

namespace App\Controller\client\food;

use App\Controller\BaseController;
use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends BaseController
{
    #[Route('food/delete/{id}', name: 'app_food_delete')]
    public function deleteFood(Food $food, EntityManagerInterface $em): Response
    {
        $em->remove($food);
        $em->flush();

        return $this->redirectToRoute('app_coach_client_food');
    }
}
