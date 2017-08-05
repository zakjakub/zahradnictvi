<?php
// src/AppBundle/Form/OrderType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', null, array('label' => "Zákazník", 'required'=>true))
            ->add('date', DateType::class, array('widget' => 'single_text', 'label'=>"Datum objednávky", 'required'=>true))
            ->add('processed', DateType::class, array('widget' => 'single_text', 'label'=>"Datum vyřízení", 'required'=>false))
            ->add('payment', DateType::class, array('widget' => 'single_text', 'label'=>"Datum platby", 'required'=>false))
            ->add(
                'orderProducts',
                CollectionType::class,
                array(
                    'entry_type'   => OrderProductType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype'    => true,
                    'delete_empty' => true,
                )
            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Order',
            )
        );
    }

    public function getName()
    {
        return 'app_bundle_order_type';
    }

}
