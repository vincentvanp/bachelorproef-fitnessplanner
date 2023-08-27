<?php

namespace App\Form;

use App\Entity\Weight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeightType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Type you weight here',
                ],
                'label' => 'Weight',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime('now'),
                'label' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add weight',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weight::class,
        ]);
    }
}
