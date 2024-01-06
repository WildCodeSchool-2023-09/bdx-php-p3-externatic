<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\User;
use http\Url;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\HtmlSanitizer\Type;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, ['label' => false,])
        #  ->add('image', DropzoneType::class, [
         #  'label' => false,
          #    'mapped' => false,
           #   'required' => false,
            # 'attr' => [
             # 'placeholder' => 'Drag and drop a file or click to browse',
           #],
       # ])
            ->add('bio', null, ['label' => false, 'required' => false,])
            // try changing this to regex to validate !
            ->add('linkedin', null, ['label' => false, 'required' => false, 'constraints' => [
                new Regex([
                    'pattern' =>
                        "^((http|https)://)[-a-zA-Z0-9@:%._\\+~#?&//=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%._
                        \\+~#?&//=]*)$^",
                    'message' => "Veuillez saisir une adresse URL valide"])]])
            ->add('location', null, ['label' => false, 'required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'sanitize_html' => true,
            # recommended by symfony when including a textarea field in a form to prevent attacks
        ]);
    }
}
