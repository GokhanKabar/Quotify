<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $company = $options['company_id'];

        $builder
            ->add('productName', TextType::class, [
                'label' => 'Nom du produit',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le nom du produit',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une description',
                    ]),
                ],
            ])
            ->add('unitPrice', NumberType::class, [
                'label' => 'Prix unitaire',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prix unitaire',
                    ]),
                    new Positive([
                        'message' => 'Le prix unitaire doit être supérieur à 0',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'categoryName',
                'label' => 'Catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'company_id' => null,
        ]);
    }
}
