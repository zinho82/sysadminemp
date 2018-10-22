<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class ItemsOcFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('cantidad', Filters\NumberFilterType::class)
            ->add('descripcion', Filters\TextFilterType::class)
            ->add('valor', Filters\NumberFilterType::class)
        
            ->add('ordenescompra', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Ordenescompra',
                    'choice_label' => 'id',
            )) 
            ->add('inventario', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Inventario',
                    'choice_label' => 'nombreProducto',
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
