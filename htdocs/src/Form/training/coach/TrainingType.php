<?php

namespace App\Form\training\coach;

use App\Entity\Training;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
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
                    'placeholder' => 'Give a short description of the training.',
                ],
            ])
            ->add('durationProposed', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Proposed duration in minutes',
                ],
                'label' => 'Actual duration (minutes)',
            ])
            ->add('clients', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'class' => User::class,
                'choices' => $options['data']->getCoach()->getClients(),
                'choice_label' => function ($choice, string $key, mixed $value): string {
                    return $choice->getFullName();
                },
            ])
            ->add('startTime', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime('now'),
            ])
            ->add('withTrainer', CheckboxType::class, [
                'label' => 'Is this a personal training session?',
                'required' => false,
            ]);
        /*->add('save', SubmitType::class, [
            'label' => 'Add Training',
            'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
        ])*/

        if (!$this->entityManager->contains($options['data'])) {
            $builder->add('save', SubmitType::class, [
                'label' => 'Add training',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
        } else {
            $builder->add('save', SubmitType::class, [
                'label' => 'Update training',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4 text-white'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
