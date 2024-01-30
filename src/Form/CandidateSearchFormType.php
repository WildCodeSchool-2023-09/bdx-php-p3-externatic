<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champ de saisie pour la fonction du candidat
        // Champ de saisie pour la localisation du candidat
        // Bouton de soumission pour lancer la recherche
        $builder
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
