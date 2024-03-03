<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email', 
            'attr' => [
                'placeholder' => 'name@company.com',
            ], 
            'required' => true, 'constraints' => [
                new Email([
                    'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                ]),
            ],
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
        ->add('address', TextType::class, [
            'label' => 'Adresse de la société',
            'attr' => [
                'placeholder' => 'Adresse de la société',
            ],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse de société valide',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Votre adresse de société doit contenir au moins {{ limit }} caractères',
                    'max' => 50,
                ]),
            ],
        ])
        ->add('phoneNumber', TextType::class, [
            'label' => 'Numéro de téléphone',
            'attr' => [
                'placeholder' => 'Numéro de téléphone',
            ],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un numéro de téléphone valide',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'Votre numéro de téléphone doit contenir au moins {{ limit }} caractères',
                    'max' => 10,
                    'maxMessage' => 'Votre numéro de téléphone doit contenir au maximum {{ limit }} caractères',
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class,]);
    }
}
