<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'required' => true,
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
            ->add('companyName', TextType::class, [
                'mapped' => false,
                'data' => $options['data']->getCompany() ? $options['data']->getCompany()->getCompanyName() : '',
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('companyAddress', TextType::class, [
                'mapped' => false,
                'data' => $options['data']->getCompany() ? $options['data']->getCompany()->getAddress() : '',
                'label' => 'Adresse de l\'entreprise',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('companyEmail', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un e-mail pour l\'entreprise',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                        ]),
                ],
                'mapped' => false,
                'data' => $options['data']->getCompany() ? $options['data']->getCompany()->getEmail() : '',
                'label' => 'E-mail de l\'entreprise',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
            ->add('companySiretNumber', TextType::class, [
                'mapped' => false,
                'data' => $options['data']->getCompany() ? $options['data']->getCompany()->getSiretNumber() : '',
                'label' => 'Numéro de SIRET de l\'entreprise',
                'attr' => ['class' => 'input border border-gray-400 rounded-md p-2 w-full'],
                'required' => false,
            ])
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
