<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Quotation;
use App\Entity\QuotationDetail;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\NotNull;

class QuotationDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('product', EntityType::class, [
            'class' => Product::class,
            'choice_label' => function($product) {
                return $product->getProductName() . ' - ' . $product->getUnitPrice();
            },
            'label' => 'Produit',
            'query_builder' => function (ProductRepository $productRepository) use ($options) {
                return $productRepository->createQueryBuilder('p')
                    ->where('p.companyReference = :company_id')
                    ->setParameter('company_id', $options['company_id']);
            },
            'choice_attr' => function($product) {
                return ['data-price' => $product->getUnitPrice()];
            },
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez choisir un produit',
                ]),
            ],
        ])
        ->add('quantity', NumberType::class, [
            'attr' => ['id' => 'quantity', 'type' => 'number', 'min' => 1, 'step' => 1, 'placeholder' => 'Quantité'],
            'label' => 'Quantité',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut pas être vide.',
                ]),
                new NotNull([
                    'message' => 'Veuillez saisir une quantité',
                ]),
                new Positive([
                    'message' => 'La quantité doit être supérieure à 0',
                ]),
            ],
        ])
        ->add('tva', NumberType::class, [
            'attr' => ['id' => 'tva', 'step' => 0.01, 'min' => 0, 'max' => 100, 'type' => 'number', 'placeholder' => 'TVA'],
            'label' => 'TVA',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut pas être vide.',
                ]),
                new NotNull([
                    'message' => 'Veuillez saisir une TVA',
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuotationDetail::class,
            'company_id' => null,
        ]);
    }
}
