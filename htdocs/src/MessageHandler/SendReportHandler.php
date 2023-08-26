<?php

namespace App\MessageHandler;

use App\Entity\Report;
use App\Entity\ReportRequest;
use App\Entity\User;
use App\Entity\Weight;
use App\Repository\TrainingRepository;
use App\Repository\WeightRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AsMessageHandler]
class SendReportHandler
{
    /**
     * @param array<Weight> $weightData
     */
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TrainingRepository $trainingRepository,
        private readonly WeightRepository $weightRepository,
        private readonly ChartBuilderInterface $chartBuilder,
        private array $weightData = [],
    ) {
    }

    public function __invoke(ReportRequest $reportRequest): void
    {
        // dd($this->generateReport($reportRequest));
        $email = (new TemplatedEmail())
            ->from('vincent.vp@icloud.com')
            ->to('test@lol.be')
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('emails/report.html.twig')
            ->context([
                'report' => $this->generateReport($reportRequest),
                'date' => $reportRequest->getMonth(),
            ]);

        $this->mailer->send($email);
    }

    private function generateReport(ReportRequest $reportRequest): Report
    {
        $user = $reportRequest->getUser();
        $report = new Report($user);
        $start = new \DateTime($reportRequest->getMonth().'-01 00:00');
        $end = new \DateTime('last day of '.$reportRequest->getMonth().' 23:59');

        $trainings = $this->trainingRepository->findTrainings($start, $end, $user->getId());

        // total advised training time
        $totalAdvisedTime = 0;

        // Array for total effective training and daily average
        $effectiveTraining = [];

        foreach ($trainings as $training) {
            $effectiveTraining[] = $training->getDurationActual();
            $totalAdvisedTime += $training->getDurationProposed();
        }

        $report->setAdvisedTrainingTime($totalAdvisedTime);

        if (empty($effectiveTraining)) {
            $report->setDailyAverage(0);
            $report->setEffectiveTrainingTime(0);
        } else {
            $report->setEffectiveTrainingTime(array_sum($effectiveTraining));
            $report->setDailyAverage($report->getEffectiveTrainingTime() / count($effectiveTraining));
        }

        $report->setTotalWeightDifference($this->getWeightDifference($start, $end, $user));

        $report->calculatePercentageCompleted();

        if (!empty($this->weightData)) {
            $report->setWeightChart($this->createGraph());
        }

        return $report;
    }

    private function getWeightDifference(\DateTime $start, \DateTime $end, User $user): float
    {
        $this->weightData = $this->weightRepository->findWeightInPeriod($start, $end, $user);

        if (empty($this->weightData)) {
            return 0.00;
        }

        return $this->weightData[0]->getValue() - $this->weightData[count($this->weightData) - 1]->getValue();
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
