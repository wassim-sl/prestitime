<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add(
                'email',
                EmailType::class,
                [

                    'label' => 'Email<span class="text-gold">*</span>',
                    'label_html' => true,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Saisir une adresse email',
                        'autocomplete' => 'new-password'
                    ],
                    'constraints' => [
                        new Email([
                            'message' => 'Veuillez saisir une adresse email valide'
                        ]),
                        new NotBlank([
                            'message' => 'Veuillez saisir une adresse email'
                        ])
                    ]
                ]
                        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
