<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PastMonthFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentMonth = (int) date('m'); // Get the current month
        $currentYear = (int) date('Y'); // Get the current year

        $pastMonths = [];

        // Calculate the last 10 months
        for ($i = 0; $i < 10; ++$i) {
            $month = $currentMonth - $i;
            $year = $currentYear;

            if ($month <= 0) {
                $month += 12;
                --$year;
            }

            $pastMonths[sprintf('%s - %d', date('F', mktime(0, 0, 0, $month, 1)), $year)] = "$year-$month";
        }

        $builder->add('selected_month', ChoiceType::class, [
            'label' => 'Select a Past Month',
            'choices' => $pastMonths, ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
