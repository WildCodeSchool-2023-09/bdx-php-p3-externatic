<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\Job;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CandidateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('github', null, ['label' => false, 'required' => false, 'constraints' => [
                new Regex([
                    'pattern' =>
                        "^((http|https)://)[-a-zA-Z0-9@:%._\\+~#?&//=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%._
                        \\+~#?&//=]*)$^",
                    'message' => "Veuillez saisir une adresse URL valide"])]])
        ->add('fonction', null, ['label' => false, 'required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'sanitize_html' => true,
        ]);
    }
}
