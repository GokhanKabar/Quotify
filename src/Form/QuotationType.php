<?php

namespace App\Form;

use App\Entity\Quotation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\QuotationDetailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $company = $options['company_id'];

        $builder
            ->add('userReference', EntityType::class, [
                'class' => User::class,
                'label' => 'Client',
                'query_builder' => function(EntityRepository $userRepository) use ($options) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.company = :company_id')
                        ->setParameter('company_id', $options['company_id']);
                },
            ])
            ->add('quotationDetails', CollectionType::class, [
                'entry_type' => QuotationDetailType::class,
                'entry_options' => [
                    'label' => false,
                    'company_id' => $options['company_id'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'=> false,
                'prototype_options' => [
                    'attr' => [
                        'class' => 'flex gap-5',
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quotation::class,
            'company_id' => null,
            'quotation' => null
        ]);
    }
}
