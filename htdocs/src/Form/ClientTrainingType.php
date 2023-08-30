<?php

namespace App\Form;

use App\Entity\Training;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientTrainingType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Title',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Give a short description of your training.',
                ],
            ])
            ->add('durationActual', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Actual duration in minutes',
                ],
                'label' => 'Actual duration (minutes)',
            ])
            ->add('coach', EntityType::class, [
                'expanded' => true,
                'multiple' => false,
                'class' => User::class,
                'choices' => $options['data']->getClients()->first()->getCoaches(),
                'choice_label' => function ($choice, string $key, mixed $value): string {
                    return $choice->getFullName();
                },
            ])
            ->add('startTime', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime('now'),
            ])
            ->add('commentClient', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Add a comment for the coach.',
                ],
                'label' => 'Comment for your coach (optional)',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Training',
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
