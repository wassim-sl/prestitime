<?php

namespace App\Form;

use App\Entity\Marque;
use PhpParser\Node\Expr\AssignOp\Div;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'placeholder' => 'Ex : Rolex, Omega, Casio...',
                    'class' => 'form-control border-info shadow-sm'
                ],
                'label' => 'Nom <span class="text-danger">*</span>',
                'label_attr' => [
                    'class' => 'fw-bold text-black'
                ],
                'label_html' => true,
                'help' => 'Saisissez une marque compris entre <span class="text-danger">2</span> et <span class="text-danger">20</span> caractères.',
                'help_attr' => [
                    'class' => 'form-text text-muted fst-italic'
                ],
                'help_html' => true,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                       
                        'message' => 'Veuillez saisir le nom de la marque'
                    ]),

                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au minimum {{ limit }} caractères',
                        'max' => 20,
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères'
                    ])

                ]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marque::class,
        ]);
    }
}
