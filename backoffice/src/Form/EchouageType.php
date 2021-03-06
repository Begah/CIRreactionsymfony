<?php

/**
 * Echouage form, created automatically by symfony CRUD and left unchanged
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

namespace App\Form;

use App\Entity\Echouage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchouageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('nombre')
            ->add('espece')
            ->add('zone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Echouage::class,
        ]);
    }
}
