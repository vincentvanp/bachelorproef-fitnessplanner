<?php

namespace App\Controller\progress;

use App\Controller\BaseController;
use App\Form\PastMonthFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends BaseController
{
    #[Route('/client/report', name: 'app_report')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PastMonthFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the selected past month
            $selectedMonth = $form->get('selected_month')->getData();
            // dd(new \DateTime($selectedMonth . '-01 00:00'));
            $period = [
                'start' => new \DateTime($selectedMonth.'-01 00:00'),
                'end' => new \DateTime('last day of '.$selectedMonth.' 23:59'),
            ];
            dd($period);

            // ...
        }

        return $this->render('report/filter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
