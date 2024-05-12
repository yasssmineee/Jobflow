<?php

namespace App\Form;

use App\Entity\Chat;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Form\FormError;

class Project1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prname')
            ->add('description')
            ->add('type')
            ->add('stdate')
            
            ->add('enddate')
            ->add('idchat', EntityType::class, [
                'class' => Chat::class,
                'choice_label' => 'id',
            ]);
            

        // Add event listener for PRE_SUBMIT event
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            // Ensure prname starts with uppercase letter
            if (isset($data['prname'])) {
                $prname = new UnicodeString($data['prname']);
                $data['prname'] = $prname->slice(0, 1)->upper() . $prname->slice(1);
            }

           if (isset($data['stdate']) && isset($data['enddate'])) {
    // Check if $data['stdate'] and $data['enddate'] are strings
    if (is_string($data['stdate']) && is_string($data['enddate'])) {
        // Parse the date strings into DateTime objects
        $stdate = \DateTime::createFromFormat('Y-m-d', $data['stdate']);
        $enddate = \DateTime::createFromFormat('Y-m-d', $data['enddate']);
        
        // Check if DateTime objects were created successfully
        if ($stdate && $enddate) {
            // Compare the dates
            if ($enddate <= $stdate) {
                $form = $event->getForm();
                $form->addError(new FormError('End date must be newer than start date.'));
            }
        } else {
            // Handle the case where date strings couldn't be parsed
            // This could happen if the date format is invalid
            // You may want to log an error or handle it in some other way
        }
    } else {
        // Handle the case where $data['stdate'] or $data['enddate'] is not a string
        // You may want to throw an exception, log an error, or handle it in some other way
    }
}
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }

}
