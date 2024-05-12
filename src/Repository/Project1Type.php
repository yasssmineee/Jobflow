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

            // Ensure enddate is newer than stdate
            if (isset($data['stdate']) && isset($data['enddate'])) {
                $stdate = new \DateTime($data['stdate']);
                $enddate = new \DateTime($data['enddate']);

                if ($enddate <= $stdate) {
                    $form = $event->getForm();
                    $form->addError(new FormError('End date must be newer than start date.'));
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
