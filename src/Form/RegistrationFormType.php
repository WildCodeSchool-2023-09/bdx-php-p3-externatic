<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, ['label' => false, 'constraints' => [
                  new Length([
                    'min' => 2,
                    'max' => 255,
                      'minMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères",
                      'maxMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères.",
                     ]),
            new Regex([
                'pattern' => '/^[a-zA-Z\s]+$/',
                'message' => "Nom d'utilisateur invalide",
                'htmlPattern' => '^[a-zA-Z\s]+$'
                  ])]])
            ->add('email', EmailType::class, ['label' => false,  'constraints' => [
                    new NotBlank(),  new Length([
                    'min' => 2,
                    'max' => 180,

                    ]),
                ]])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => false],
                'second_options' => ['label' => false],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,

                    ]),
                   new Assert\PasswordStrength([ 'minScore' => PasswordStrength::STRENGTH_WEAK,
                        'message' => 'Veuillez choisir un mot de passe plus forte',
                            ])  //I chose strength weak as the default value of medium was too strong! //
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Un candidat' => 'ROLE_CANDIDAT',
                    'Une entreprise' => 'ROLE_COMPANY',
                ],

            ])
            ->get('roles')->addModelTransformer(new CallbackTransformer(
                fn($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0] : null,
                fn($rolesAsString) => [$rolesAsString]
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
