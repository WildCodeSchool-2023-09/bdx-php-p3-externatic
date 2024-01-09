<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret', NumberType::class, ['label' => false,  'constraints' => [
        new NotBlank(),  new Length([
            'min' => 14,
            'max' => 14,
                    'minMessage' => 'veuillez saisir un numéro de siret valide',
                    'maxMessage' => 'veuillez saisir un numéro de siret valide'
            ])]])
            ->add('adress', null, ['label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'sanitize_html' => true,
        ]);
    }
}
