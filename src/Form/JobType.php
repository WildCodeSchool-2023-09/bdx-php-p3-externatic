<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => false])
            ->add('adress', null, ['label' => false])
            ->add('description', null, ['label' => false])
            ->add('startDate', null, ['label' => false])
            ->add('salary', null, ['label' => false])
            ->add('city', null, ['label' => false])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
                'attr' => [
                    'style' => 'display: none;', // Cela masquera le champ dans le formulaire
                ],
                'label' => false, // Cela masquera le label du champ dans le formulaire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
