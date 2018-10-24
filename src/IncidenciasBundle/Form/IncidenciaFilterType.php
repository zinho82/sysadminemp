<?php

namespace IncidenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class IncidenciaFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('fechaIncidencia', Filters\DateTimeFilterType::class)
            ->add('numeroIncidencia', Filters\TextFilterType::class)
            ->add('descripcionError', Filters\TextFilterType::class)
            ->add('fechaSolucion', Filters\DateTimeFilterType::class)
            ->add('solucion', Filters\TextFilterType::class)
            ->add('fechaIngreso', Filters\DateTimeFilterType::class)
            ->add('archivo', Filters\TextFilterType::class)
        
            ->add('ingresasoPor', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Usuario',
                    'choice_label' => 'username',
            )) 
            ->add('estado', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
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
