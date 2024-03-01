<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez entrer un e-mail',
                    ]),
                ],
                'attr' => ['placeholder' => 'E-mail', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'label' => 'E-mail',
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field border border-gray-400 rounded-md p-2 w-full']],
                'required' => false,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['placeholder' => 'Nouveau mot de passe', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                ],
                'second_options' => [
                    'label' => 'Répéter le nouveau mot de passe',
                    'attr' => ['placeholder' => 'Répéter le nouveau mot de passe', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                        'groups' => ['password_change'],
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                        'maxMessage' => 'Votre mot de passe doit comporter au maximum {{ limit }} caractères',
                        'groups' => ['password_change'],
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
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
                'attr' => ['placeholder' => 'Nom', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
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
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => ['placeholder' => 'Numéro de téléphone', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Adresse', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ville', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'attr' => ['placeholder' => 'Code Postal', 'class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ],
                'label' => 'Genre',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
                'placeholder' => 'Sélectionnez votre genre',
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'company_name',
                'label' => 'Entreprise',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'placeholder' => 'Sélectionnez une entreprise',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-700 cursor-pointer'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => function ($form) {
                if ($form->getData()->getPlainPassword() !== null) {
                    return ['Default', 'password_change'];
                }
                return ['Default'];
            },
        ]);
    }
}
