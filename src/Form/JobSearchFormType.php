<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du Job',
                'attr' => ['placeholder' => 'Entrez le titre du Job'],
                'required' => false, // Ne pas rendre le champ obligatoire
            ])
            ->add('city', TextType::class, [
                'label' => 'Lieu',
                'attr' => ['placeholder' => 'Entrez le titre du Job'],
                'required' => false, // Ne pas rendre le champ obligatoire

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
