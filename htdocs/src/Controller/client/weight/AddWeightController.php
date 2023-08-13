<?php

namespace App\Controller\client\weight;

use App\Controller\BaseController;
use App\Entity\Weight;
use App\Form\WeightType;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddWeightController extends BaseController
{
    #[Route('/client/weight/add', name: 'app_weight_add')]
    public function addWeight(Request $request, EntityManagerInterface $em, WeightRepository $weightRepository): Response
    {
        $weight = new Weight();
        $weight->setUser($this->getUser());

        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weight = $form->getData();
            $oldWeights = $weightRepository->findWeightByDate($weight->getDate(), $weight->getUser());
            foreach ($oldWeights as $oldWeight) {
                $em->remove($oldWeight);
            }
            $em->persist($weight);
            $em->flush();

            return $this->redirectToRoute('app_weight');
        }

        return $this->render('weight/add.html.twig', [
            'form' => $form,
        ]);
    }
}
