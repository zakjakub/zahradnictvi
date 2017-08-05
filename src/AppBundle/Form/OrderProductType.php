<?php
// src/AppBundle/Form/OrderProductType.php

namespace AppBundle\Form;

use AppBundle\Entity\ProductType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('product')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\OrderProduct',
            )
        );
    }

    public function getName()
    {
        return 'app_bundle_order_product_type';
    }
}
