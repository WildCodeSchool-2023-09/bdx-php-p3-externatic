<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\CVs;
use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Validator\Constraints\File;

class ApplyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champ pour le message
        // Choix du CV
        // Champ pour le nouveau CV avec l'ajout de Dropzone pour le téléchargement de fichiers
        $builder
            ->add('message', null, ['label' => false ])
            ->add('cvChoice', ChoiceType::class, [
                'choices' => [
                    'CV par défaut' => 'default',
                    'Nouveau CV' => 'new',
                ],
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('newCV', DropzoneType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document PDF valide',
                    ])]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
