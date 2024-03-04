<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class OpportuniteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q', SearchType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => 'Search opportunities',
                'class' => 'form-control mr-sm-2'
            ]
        ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'stage'=>'stage',
                    'travail'=>'travail'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control mr-sm-2'
                ]
            ])

            ->add('sort_by', ChoiceType::class, [
                'choices' => [
                    'nom' => 'nom',
                    
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control mr-sm-2'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET'
        ]);
    }
}