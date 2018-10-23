<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class AutorizacionesFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('fechaAutorizacion', Filters\DateTimeFilterType::class)
            ->add('comentario', Filters\TextFilterType::class)
        
            ->add('solicitud', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Solicitudes',
                    'choice_label' => 'valor',
            )) 
            ->add('ordenescompra', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Ordenescompra',
                    'choice_label' => 'id',
            )) 
            ->add('autorizadoPor', Filters\EntityFilterType::class, array(
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
