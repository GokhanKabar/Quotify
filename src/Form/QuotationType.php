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

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('creationDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Accepté' => 'Approuvé',
                    'Refusé' => 'Rejeté',
                ],
            ])
            ->add('userReference', EntityType::class, [
                'class' => User::class,
                'query_builder' => function(EntityRepository $userRepository) use ($options) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.company = :company_id')
                        ->setParameter('company_id', $options['company_id']);
                },
            ])
            ->add('totalHT')
            ->add('totalTTC')
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
