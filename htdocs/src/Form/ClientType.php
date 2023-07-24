<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-4',
                    'id' => 'inputEmail',
                    'type' => 'email',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-4',
                    'type' => 'password',
                    'id' => 'inputPassword',
                    'placeholder' => 'Create a password',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-4',
                    'id' => 'inputFirstName',
                    'type' => 'text',
                    'placeholder' => 'First name',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-4',
                    'id' => 'inputFirstName',
                    'type' => 'text',
                    'placeholder' => 'Last name',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create account',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block w-100 mt-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
