<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'name@company.com',
            ],
            'required' => true,
            'constraints' => [
                new Email([
                    'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                ]),
            ],
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mettre un mot de passe svp',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ton mot de passe devrait avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe',
                ],
            ],
            'second_options' => [
                'label' => 'Répéter votre mot de passe',
                'attr' => [
                    'placeholder' => 'Répéter votre mot de passe'
                ],
            ],
            'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
        ])
        ->add('company', EntityType::class, [
            'class' => Company::class,
            'choice_label' => 'companyName',
            'label' => 'Entreprise',
            'attr' => [
                'placeholder' => 'Entreprise',
            ],
            'required' => true,
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Prénom',
            ],
            'required' => true,
            'constraints' => [
                new Length([
                    'min' => 2,
                    'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre prénom doit contenir au maximum {{ limit }} caractères',
                ]),
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Nom',
            ],
            'required' => true,
            'constraints' => [
                new Length([
                    'min' => 2,
                    'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre nom doit contenir au maximum {{ limit }} caractères',
                ]),
            ],
        ])
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_COMPANY' => 'ROLE_COMPANY',
                'ROLE_ACCOUNTANT' => 'ROLE_ACCOUNTANT',
            ],
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
