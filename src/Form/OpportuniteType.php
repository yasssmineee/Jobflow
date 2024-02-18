<?php

namespace App\Form;

use App\Entity\Opportunite;
use App\Entity\test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpportuniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('descreption')
            ->add('type',ChoiceType::class ,
            [
                'choices'=>[
                    'test'=>'test'
                ]
                ],
        
            )
            ->add('idtest', EntityType::class, [
                'class' => Test::class,
'choice_label' => 'id',
            ]);
        }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opportunite::class,
        ]);
    }

}
