<?php

namespace RrhhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class RrhhFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('nombre', Filters\TextFilterType::class)
            ->add('apellidoPaterno', Filters\TextFilterType::class)
            ->add('apellidoMaterno', Filters\TextFilterType::class)
            ->add('rut', Filters\TextFilterType::class)
            ->add('direccion', Filters\TextFilterType::class)
            ->add('comuna', Filters\TextFilterType::class)
            ->add('ciudad', Filters\TextFilterType::class)
            ->add('region', Filters\TextFilterType::class)
            ->add('sueldoBruto', Filters\TextFilterType::class)
            ->add('correoElectronico', Filters\TextFilterType::class)
        
            ->add('cargo', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
            )) 
            ->add('afp', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
            )) 
            ->add('institucionSalud', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
            )) 
            ->add('empresa', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Empresa',
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
