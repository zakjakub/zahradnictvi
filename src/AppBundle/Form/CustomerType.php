<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => "Jméno a příjmení"))
            ->add('street', TextType::class, array('label' => "Ulice", 'required' => false))
            ->add('house_number', NumberType::class, array('label' => "Číslo popisné", 'required' => false))
            ->add('city', TextType::class, array('label' => "Město", 'required' => false))
            ->add('postal_code', TextType::class, array('label' => "PSČ", 'required' => false))
            ->add('email', EmailType::class, array('label' => "E-mail", 'required' => false))
            ->add('phone', TextType::class, array('label' => "Telefon", 'required' => false))
            ->add('save', SubmitType::class, array('label' => "Uložit"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Customer',
            )
        );
    }

    public function getName()
    {
        return 'app_bundle_customer_type';
    }
}
