<?php

namespace App\Form;

use App\Entity\Echouage;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccueilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('Zone', ChoiceType::class, [
            'choices'  => $options['zones'],
        ])
        ->add('Espece', TextType::class, [
            'required' => true,
            'empty_data' => '',
            'attr'=>['autocomplete' => 'off']
        ])
        ->add('Submitted', HiddenType::class, [
            'data' => 0,
        ]);
        
        $builder->get('Submitted')->addEventListener(FormEvents::PRE_SUBMIT, 
        function (FormEvent $event) {
            if ($event->getData() < 3) {
                $event->setData($event->getData() + 1);
            }
        });
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'zones' => [
                'All' => -1,
            ],
        ]);
    }
}
