<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            
            ->add('contenu')
            ->add('tag')
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG file)',
                'mapped' => false, // Tells Symfony that this field is not mapped to any property of the entity
                'required' => false, // Set it to false if the image is not mandatory
                'attr' => ['accept' => '.jpg,.png'] // Define accepted file types
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
