<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_COMPANY' => 'ROLE_COMPANY',
                    'ROLE_ACCOUNTANT' => 'ROLE_ACCOUNTANT',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('isVerified')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
