<?php

namespace App\Controller\client\food;

use App\Controller\BaseController;
use App\Repository\FoodRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route('/client/food', name: 'app_client_food')]
    public function clientFood(FoodRepository $foodRepository, Request $request): Response
    {
        $data = ['date' => new \DateTime('now')];

        $form = $this->createFormBuilder($data)
            ->add('date', DateType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $food = $foodRepository->findBy([
            'date' => $data['date'],
            'user' => $this->getUser(),
        ]);

        return $this->render('client/food/index.html.twig', [
            'foods' => $food,
            'dateFilter' => $form->createView(),
        ]);
    }
}
