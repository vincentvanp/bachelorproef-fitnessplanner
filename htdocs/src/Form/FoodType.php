<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Title',
                ],
            ])
            ->add('description', TextAreaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Give a short description of your meal.',
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime('now'),
                'label' => false,
            ])
            ->add('category', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Dinner, Snack, Breakfast,...',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Meal',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
        ]);
    }
}
