<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class FacturasFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('fecha', Filters\DateTimeFilterType::class)
            ->add('fechaIngreso', Filters\DateTimeFilterType::class)
            ->add('fechaPago', Filters\DateTimeFilterType::class)
            ->add('numeroFactura', Filters\NumberFilterType::class)
            ->add('neto', Filters\NumberFilterType::class)
        
            ->add('campana', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Campana',
                    'choice_label' => 'descripcion',
            )) 
            ->add('itemGasto', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Ordenescompra',
                    'choice_label' => 'id',
            )) 
            ->add('estadoPago', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
            )) 
            ->add('departamento', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Departamentos',
                    'choice_label' => 'nombreDepartamento',
            )) 
            ->add('empresa', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Empresa',
                    'choice_label' => 'nombre',
            )) 
            ->add('ordenescompra', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Ordenescompra',
                    'choice_label' => 'id',
            )) 
            ->add('proveedoresClientes', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\ProveedoresClientes',
                    'choice_label' => 'nombre',
            )) 
        ;
        $builder->setMethod("GET");


    }

    public function getBlockPrefix()
    {
        return null;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
