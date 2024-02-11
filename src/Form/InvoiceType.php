<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\InvoiceDetailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $company = $options['company_id'];

        $builder
            ->add('paymentStatus', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                    'Non payé' => 'Non payé',
                ],
            ])
            ->add('userReference', EntityType::class, [
                'class' => User::class,
                'label' => 'Client',
                'query_builder' => function(EntityRepository $userRepository) use ($options) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.company = :company_id')
                        ->setParameter('company_id', $options['company_id']);
                },
            ])
            ->add('totalHT', HiddenType::class, [
                'label' => 'Total HT',
            ])
            ->add('totalTTC', HiddenType::class, [
                'label' => 'Total TTC',
            ])
            ->add('invoiceDetails', CollectionType::class, [
                'entry_type' => InvoiceDetailType::class,
                'entry_options' => [
                    'label' => false,
                    'company_id' => $options['company_id'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'=> false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'company_id' => null,
        ]);
    }
}
