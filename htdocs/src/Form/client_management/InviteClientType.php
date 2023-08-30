<?php

namespace App\Form\client_management;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InviteClientType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail of client',
                'attr' => [
                    'class' => 'form-control w-50',
                    'id' => 'inputEmail',
                    'type' => 'email',
                    'placeholder' => 'email@example.com',
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control w-50',
                    'placeholder' => 'Add a personal message for the client.',
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Invite client',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-50 mt-4 text-white'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
