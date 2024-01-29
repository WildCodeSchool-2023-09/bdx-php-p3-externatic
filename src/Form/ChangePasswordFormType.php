<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champ de mot de passe répété
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe',
                        ]),
                        new PasswordStrength([ 'minScore' => PasswordStrength::STRENGTH_WEAK,
                            'message' => 'Veuillez choisir un mot de passe plus forte',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            // Longueur maximale autorisée par Symfony pour des raisons de sécurité
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe', 'label_attr' => [
                        'class' => 'label']

                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],

                    'label' => 'Répéter le mot de passe', 'label_attr' => [
                        'class' => 'label']
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
