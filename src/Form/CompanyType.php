<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Nom de la société',
                'attr' => [
                    'placeholder' => 'Nom de la société',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom de société valide',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom de société doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email de la société',
                'attr' => [
                    'placeholder' => 'Email de la société',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un email de société valide',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre email de société doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
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
            ->add('siretNumber', TextType::class, [
                'label' => 'Numéro de siret de la société',
                'attr' => [
                    'placeholder' => 'Numéro de siret de la société',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro de siret de société valide',
                    ]),
                    new Length([
                        'min' => 14,
                        'minMessage' => 'Votre numéro de siret de société doit contenir au moins {{ limit }} caractères',
                        'max' => 14,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
