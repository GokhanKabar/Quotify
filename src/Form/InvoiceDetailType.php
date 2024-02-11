<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\InvoiceDetail;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class InvoiceDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'attr' => ['id' => 'quantity'],
                'label' => 'Quantité',
            ])
            ->add('tva', NumberType::class, [
                'attr' => ['id' => 'tva', 'step' => 0.01, 'min' => 0, 'max' => 100],
                'label' => 'TVA',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => function($product) {
                    return $product->getProductName() . ' - ' . $product->getUnitPrice();
                },
                'label' => 'Product',
                'query_builder' => function (ProductRepository $productRepository) use ($options) {
                    return $productRepository->createQueryBuilder('p')
                        ->where('p.companyReference = :company_id')
                        ->setParameter('company_id', $options['company_id']);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceDetail::class,
            'company_id' => null,
        ]);
    }
}
