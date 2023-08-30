<?php

namespace App\MessageHandler;

use App\Entity\Report;
use App\Entity\ReportRequest;
use App\Entity\User;
use App\Repository\TrainingRepository;
use App\Repository\WeightRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendReportHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TrainingRepository $trainingRepository,
        private readonly WeightRepository $weightRepository
    ) {
    }

    public function __invoke(ReportRequest $reportRequest): void
    {
        // dd($this->generateReport($reportRequest));
        $email = (new TemplatedEmail())
            ->from('vincent.vp@icloud.com')
            ->to($reportRequest->getEmail())
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
            $report->setDailyAverage($this->calculateDailyAverage($start, $end, $report->getEffectiveTrainingTime()));
        }

        $report->setTotalWeightDifference($this->getWeightDifference($start, $end, $user));

        $report->calculatePercentageCompleted();

        return $report;
    }

    private function getWeightDifference(\DateTime $start, \DateTime $end, User $user): float
    {
        $weightData = [];
        $weightData = $this->weightRepository->findWeightInPeriod($start, $end, $user);

        if (empty($weightData)) {
            return 0.00;
        }

        return $weightData[count($weightData) - 1]->getValue() - $weightData[0]->getValue();
    }

    private function calculateDailyAverage(\DateTime $start, \DateTime $end, int $effectiveTrainingTime): int
    {
        $interval = $start->diff($end);

        return $effectiveTrainingTime / $interval->days;
    }
}
