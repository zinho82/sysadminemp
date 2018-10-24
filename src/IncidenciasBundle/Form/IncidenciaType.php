<?php

namespace IncidenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaIncidencia')
            ->add('numeroIncidencia')
            ->add('descripcionError')
            ->add('fechaSolucion')
            ->add('solucion')
            ->add('fechaIngreso')
            ->add('archivo')
            ->add('ingresasoPor', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Usuario',
                'choice_label' => 'username',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('estado', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
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
            'data_class' => 'BackendBundle\Entity\Incidencia'
        ));
    }
}
