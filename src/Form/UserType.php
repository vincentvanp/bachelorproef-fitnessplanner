<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputEmail',
                    'type' => 'email',
                    'placeholder' => 'name@example.com',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'password',
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'password',
                    'id' => 'inputPassword',
                    'placeholder' => 'Create a password',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First name',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputFirstName',
                    'type' => 'text',
                    'placeholder' => 'Enter a name',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputFirstName',
                    'type' => 'text',
                    'placeholder' => 'Enter a name',
                ],
            ])    
            ->add('save', SubmitType::class, [
                'label' => 'Create account',
                'attr' => ['class' => 'btn btn-primary btn-block'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
