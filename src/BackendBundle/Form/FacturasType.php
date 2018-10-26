<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('fechaIngreso')
            ->add('fechaPago')
            ->add('numeroFactura')
            ->add('neto')
            ->add('campana', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Campana',
                'choice_label' => 'descripcion',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('itemGasto', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Ordenescompra',
                'choice_label' => 'id',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('estadoPago', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('departamento', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Departamentos',
                'choice_label' => 'nombreDepartamento',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('empresa', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Empresa',
                'choice_label' => 'nombre',
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
            ->add('proveedoresClientes', EntityType::class, array(
                'class' => 'BackendBundle\Entity\ProveedoresClientes',
                'choice_label' => 'nombre',
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
            'data_class' => 'BackendBundle\Entity\Facturas'
        ));
    }
}
