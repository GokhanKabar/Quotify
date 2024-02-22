<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, ['label' => 'Email', 'attr' => ['placeholder' => 'name@company.com',], 'required' => true, 'constraints' => [new Email(['message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',]),],])
            //->add('roles')
            //->add('password')
            ->add('firstname', TextType::class, ['label' => 'Prénom', 'attr' => ['placeholder' => 'Prénom',],])
            ->add('lastname', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => 'Nom',],])
            //->add('createdAt', DateTimeType::class)
            //->add('updatedAt', DateTimeType::class)
            //->add('company', EntityType::class, [
            //   'class' => Company::class,
            //    'choice_label' => 'companyName',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class,]);
    }
}
