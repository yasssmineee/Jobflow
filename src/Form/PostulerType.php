<?php

namespace App\Form;

use App\Entity\Opportunite;
use App\Entity\Postuler;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motivationText',TextareaType::class, [
                'label'=> '',            ]  
            )
            ->add('cv', FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the project image(.png .gif .jpg .jpeg)',
                    ]),
                
                ],

                'mapped' => false,
                'required' => false,

            ])
            ->add('idOpportunite', EntityType::class, [
                'class' => Opportunite::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postuler::class,
        ]);
    }


    
}