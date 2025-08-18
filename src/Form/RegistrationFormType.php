<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
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
            )

            ->add('prenom', TextType::class, [
                'label' => 'Prénom <span class="text-gold">*</span>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir votre prénom',
                    'autocomplete' => 'given-name',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ]),
                ],
            ])

            ->add('nom', TextType::class, [
                'label' => 'Nom <span class="text-gold">*</span>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir votre nom',
                    'autocomplete' => 'family-name',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                ],
            ])


            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les conditions d'utilisation ",
                'mapped' => false, // ce n'est pas une propriété dans l'entité Client 
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
