<?php


namespace App\Form;

use App\Entity\Opportunite;
use App\Entity\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OpportuniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, ['attr'=>[
                'class'=>'form-control border-0'
            ]])
            ->add('descreption',TextType::class, ['attr'=>[
                'class'=>'form-control border-0'
            ]])
            ->add('type',ChoiceType::class ,
            [
                'choices'=>[
                    'stage'=>'stage',
                    'travail'=>'travail'
                ],
               
                ],
        
            )
            ->add('idtest', EntityType::class, [
                'class' => Test::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-control border-0'
                ]
            ]);
        }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opportunite::class,
        ]);
    }

}