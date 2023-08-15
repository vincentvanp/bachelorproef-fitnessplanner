<?php

namespace App\Form;

use App\Entity\AccountSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountSettingsType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ptUpdates', CheckboxType::class, [
                'label' => 'New personal training session',
                'required' => false,
            ])
            ->add('newTraining', CheckboxType::class, [
                'label' => 'Every new training',
                'required' => false,
            ])
            ->add('newReview', CheckboxType::class, [
                'label' => 'New reviews',
                'required' => false,
            ])
            ->add('monthlyProgress', CheckboxType::class, [
                'label' => 'Monthly progress update',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountSettings::class,
        ]);
    }
}
