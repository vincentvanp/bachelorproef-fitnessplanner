<?php

namespace App\Controller\progress;

use App\Controller\BaseController;
use App\Entity\ReportRequest;
use App\Entity\User;
use App\Form\PastMonthFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends BaseController
{
    #[Route('/report/{id}', name: 'app_report', defaults: ['id' => null])]
    public function index(
        Request $request,
        MessageBusInterface $messageBus,
        User $user = null
    ): Response {
        if (null == $user) {
            $user = $this->getUser();
        }
        $form = $this->createForm(PastMonthFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch(
                new ReportRequest($form->get('selected_month')->getData(),
                    $user,
                    $this->getUser()->getEmail())
            );

            $this->addFlash('success', 'Your report is on its way.');

            return $this->redirectToRoute('app_default');
        }

        return $this->render('report/filter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
