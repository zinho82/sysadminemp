<?php

namespace NotificacionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class NotificacionesFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('descripcion', Filters\TextFilterType::class)
            ->add('fecha', Filters\DateTimeFilterType::class)
        
            ->add('area', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Departamentos',
                    'choice_label' => 'nombreDepartamento',
            )) 
            ->add('usuario', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Usuario',
                    'choice_label' => 'username',
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
