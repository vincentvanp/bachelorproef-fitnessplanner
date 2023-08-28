<?php

namespace App\Form\delete_verification;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerificationType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('confirm', CheckboxType::class, [
                'label' => 'I am sure I want to delete this training',
                'required' => true,
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'Delete training',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
