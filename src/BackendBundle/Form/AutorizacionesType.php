<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutorizacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaAutorizacion')
            ->add('comentario')
            ->add('solicitud', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Solicitudes',
                'choice_label' => 'valor',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('ordenescompra', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Ordenescompra',
                'choice_label' => 'id',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('autorizadoPor', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Usuario',
                'choice_label' => 'username',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Autorizaciones'
        ));
    }
}
