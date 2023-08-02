<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\User;
use App\Form\FoodReviewType;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FoodController extends BaseController
{
    public function __construct(private readonly EntityManagerInterface $em)
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

    #[Route('/client/food', name: 'app_coach_client_food')]
    public function clientFood(FoodRepository $foodRepository, Request $request): Response
    {
        $defaultData = ['date' => new \DateTime('now')];

        $form = $this->createFormBuilder($defaultData)
            ->add('date', DateType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $food = $foodRepository->findBy([
            'date' => $defaultData['date'],
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $food = $foodRepository->findBy([
                'date' => $data['date'],
                'user' => $this->getUser(),
            ]);
        }

        return $this->render('client/food/index.html.twig', [
            'food' => $food,
            'dateFilter' => $form->createView(),
        ]);
    }

    #[Route('/food/{id}/detail', name: 'app_food_detail')]
    public function foodDetail(Food $food, Request $request): Response
    {
        if ('client' == $this->getUser()->getRole()->first()->getName()) {
            return $this->render('client/food/detail.html.twig', [
                'food' => $food,
            ]);
        }

        $form = $this->createForm(FoodReviewType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food = $form->getData();
            $this->em->persist($food);
            $this->em->flush();

            // TODO Juiste redirect terug en testen
        }

        return $this->render('client/food/detail.html.twig', [
            'food' => $food,
            'form' => $form,
        ]);
    }

    #[Route('/food/add', name: 'app_food_add')]
    public function addFood(Request $request): Response
    {
        $food = new Food();
        $food->setUser($this->getUser());

        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFood = $form->getData();
            $this->em->persist($newFood);
            $this->em->flush();

            return $this->redirectToRoute('app_coach_client_food');
        }

        return $this->render('client/food/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('food/delete/{id}', name: 'app_food_delete')]
    public function deleteFood(Food $food): Response
    {
        $this->em->remove($food);
        $this->em->flush();

        return $this->redirectToRoute('app_coach_client_food');
    }

    #[Route('/food/edit/{id}', name: 'app_food_edit')]
    public function editFood(Request $request, Food $food): Response
    {
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFood = $form->getData();
            $this->em->persist($newFood);
            $this->em->flush();

            return $this->redirectToRoute('app_food_detail', ['id' => $food->getId()]);
        }

        return $this->render('client/food/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
