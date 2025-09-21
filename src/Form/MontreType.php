<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Montre;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'nom',
                'placeholder' => 'choisir la marque du montre',
                'label' => 'Marque<span class="text-danger">*</span>',
                'label_html' => true,
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'attr' => ['class' => 'form-select'],


                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner la marque du montre'
                    ]),
                ]


            ])
            ->add('description', null, [

                'label' => 'Description <span class="text-warning">(Facultative)</span>',
                'label_attr' => [
                    'class' => 'form-label fw-bold text-dark'
                ],
                'label_html' => true,
                'attr' => [
                    // k => v
                    'placeholder' => 'Saisir la description de la montre',
                    'class' => 'form-control'
                ],
                'required' => false,
                'help' => 'La description de la montre doit être au maximum de <span class="text-danger">200</span> caractères',
                'help_attr' => [
                    'class' => 'form-text fst-italic'
                ],
                'help_html' => true,
                'row_attr' => [
                    'class' => 'mb-4'
                ],

                'constraints' => [
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'Veuillez saisir une description avec au maximum {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix<span class="text-danger">*</span>',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'form-label fw-bold text-dark'
                ],
                'attr' => [
                    // k => v
                    'placeholder' => 'Saisir le prix de la montre',
                    'class' => 'form-control'
                ],



                'help' => 'Le prix de la montre doit être strictement supérieur à <span class="text-danger">0</span>',
                'help_attr' => [
                    'class' => 'form-text text-muted'
                ],
                'help_html' => true,
                'row_attr' => [
                    'class' => 'mb-4'
                ],
                //'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prix de la montre'
                    ]),
                    new Positive([
                        'message' => 'Veuillez saisir un nombre strictement supérieur à zéro'
                    ])
                ]
            ])

            ->add('image', null, [
                'label' => 'Image <span class="text-danger">*</span>',
                'label_html' => true,
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'attr' => [
                    'placeholder' => 'Ex : montre1_img1.jpg',
                    'class' => 'form-control',
                ],
                //'required' => false,
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('image2', null, [
                'label' => 'Image (URL ou nom de fichier)',
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'attr' => [
                    'placeholder' => 'Ex : montre1_img2.jpg',
                    'class' => 'form-control',
                ],
                'required' => false,
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('image3', null, [
                'label' => 'Image (URL ou nom de fichier)',
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'attr' => [
                    'placeholder' => 'Ex : montre1_img3.jpg',
                    'class' => 'form-control',
                ],
                'required' => false,
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('image4', null, [
                'label' => 'Image (URL ou nom de fichier)',
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'attr' => [
                    'placeholder' => 'Ex : montre1_img4.jpg',
                    'class' => 'form-control',
                ],
                'required' => false,
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('categorie', EntityType::class, [ // EntityType ==> Relation (Recherche en BDD)
                'class' => Categorie::class, // Définir quelle class (==> table)
                //'choice_label' => 'title', // Afficher quelle propriété
                'choice_label' => 'nom',
                'placeholder' => '-- Sélectionner la catégorie --',
                //'expanded' => true, // permet de transformer la balise select soit en radio soit en checkbox (en fonction de la relation)
                //'multiple' => true, // option à définir pour les relations MANY
                'label' => 'Catégorie<span class="text-danger">*</span>',
                'label_attr' => ['class' => 'form-label fw-bold text-dark'],
                'label_html' => true,
                'row_attr' => [
                    'class' => 'form-label fw-bold text-primary'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner la catégorie de la montre'
                    ]),
                ],
                'query_builder' => function (CategorieRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC')
                    ;
                }
            ])
            ->add('isActive', CheckboxType::class, [

                'label' => 'Activer cette montre',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',

                ],
                'row_attr' => [
                    'class' => 'form-check form-switch'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Montre::class,
        ]);
    }
}
