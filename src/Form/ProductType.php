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
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une catégorie',
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Vignette du produit',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'delete_label' => 'Supprimer l\'image ?',
                'attr' => [
                    'accept' => 'image/*',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 5 Mo',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un fichier image valide',
                    ]),
                    new Image([
                        'maxWidth' => 1920,
                        'maxHeight' => 1080,
                        'maxWidthMessage' => 'La largeur de l\'image ne doit pas dépasser 1000 pixels',
                        'maxHeightMessage' => 'La hauteur de l\'image ne doit pas dépasser 1000 pixels',
                    ]),
                ],
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
