<?php

namespace App\Form\training\coach;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewTrainingType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentCoach', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Give a short comment on your clients performance.',
                ],
            ])
            ->add('reviewed', CheckboxType::class, [
                'label' => 'Mark as reviewed?',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Review Training',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
