<?php

namespace App\Form;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('score',TextType::class, ['attr'=>[
            'class'=>'form-control border-0'
        ]])
        ->add('duree',TextType::class, ['attr'=>[
            'class'=>'form-control border-0'
        ]])
        ->add('type',ChoiceType::class ,
        [
            'choices'=>[
                'code'=>'code',
                'quiz'=>'quiz'
            ],
            'attr'=>[
                'class'=>'form-control border-0'
            ]
            ],
    
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}
