<?php

namespace App\Form\datefilter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('end', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm text-white'],
                'label' => 'Filter',
            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
