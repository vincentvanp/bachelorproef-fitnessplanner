<?php

namespace App\Controller\client\weight;

use App\Controller\BaseController;
use App\Entity\Weight;
use App\Repository\WeightRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class WeightIndexController extends BaseController
{
    /**
     * @param array<Weight> $weightData
     */
    public function __construct(
        private readonly WeightRepository $weightRepository,
        private readonly ChartBuilderInterface $chartBuilder,
        private string $period = 'week',
        private array $weightData = [],
    ) {
    }

    #[Route('/client/weight', name: 'app_weight')]
    public function index(Request $request): Response
    {
        $this->updateWeights();
        $form = $this->createFilterForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->period = $data['period'];
            $this->updateWeights();
        }
        $chart = 'No data for this period.';
        if (!empty($this->weightData)) {
            $chart = $this->createGraph();
        }

        return $this->render('weight/index.html.twig', [
            'form' => $form,
            'chart' => $chart,
        ]);
    }

    private function createFilterForm(): FormInterface
    {
        $defaultData = ['period' => $this->period];

        return $this->createFormBuilder($defaultData)
            ->add('period', ChoiceType::class, [
                'choices' => [
                    'week' => 'week',
                    'month' => 'month',
                    'year' => 'year',
                ],
            ])
            ->add('filter', SubmitType::class)
            ->getForm();
    }

    private function updateWeights(): void
    {
        $start = new \DateTime('now -1 week');
        $end = new \DateTime('now');
        if ('month' == $this->period) {
            $start = new \DateTime('now -1 month');
        }
        if ('year' == $this->period) {
            $start = new \DateTime('now -1 year');
        }

        $this->weightData = $this->weightRepository->findWeightInPeriod($start, $end, $this->getUser());
    }

    private function createGraph(): Chart
    {
        $graphArray = [];
        foreach ($this->weightData as $weight) {
            $graphArray['labels'][] = $weight->getDate()->format('d-m-Y');
            $graphArray['values'][] = $weight->getValue();
        }
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $graphArray['labels'],
            'datasets' => [
                [
                    'label' => 'Weight',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $graphArray['values'],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
    }
}
