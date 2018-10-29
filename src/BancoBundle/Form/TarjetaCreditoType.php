<?php

namespace BancoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TarjetaCreditoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroTarjetaCredito',texttype::class,array(
                 'attr'  =>  array(
                    'class' =>  'form-control'
                ),
            ))
            ->add('banco', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Banco',
                'choice_label' => 'numeroCuenta',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
                'label' =>  "Numero de cuenta Asociado"
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\TarjetaCredito'
        ));
    }
}
