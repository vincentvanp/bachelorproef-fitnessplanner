<?php

namespace App\Form;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteTrainingType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('durationActual', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Actual duration in minutes',
                ],
                'label' => 'Actual duration (minutes)',
            ])
            ->add('commentClient', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Add a comment for the coach.',
                ],
                'label' => 'Comment for your coach (optional)',
                'required' => false,
            ])
            ->add('startTime', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime('now'),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Complete training',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
