<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('localisation')
            ->add('date')
            ->add('heure')
            ->add('nb_participant')
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG file)',
                'required' => false, // Adjust according to your needs
                'mapped' => false,
                'attr' => ['accept' => 'image/jpeg, image/png'], // Limit accepted file types
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
