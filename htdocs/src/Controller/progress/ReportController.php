<?php

namespace App\Controller\progress;

use App\Controller\BaseController;
use App\Entity\ReportRequest;
use App\Form\PastMonthFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends BaseController
{
    #[Route('/client/report', name: 'app_report')]
    public function index(Request $request, MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(PastMonthFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch(new ReportRequest($form->get('selected_month')->getData(), $this->getUser()));
            $this->addFlash('success', 'Your report is on its way.');

            return $this->redirectToRoute('app_default');
        }

        return $this->render('report/filter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
