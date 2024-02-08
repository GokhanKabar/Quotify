<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('creationDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('paymentStatus', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                    'Non payé' => 'Non payé',
                ],
            ])
            ->add('userReference')
            ->add('totalHT')
            ->add('totalTTC')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
