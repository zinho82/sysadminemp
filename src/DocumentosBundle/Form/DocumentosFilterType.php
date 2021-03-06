<?php

namespace DocumentosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class DocumentosFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('archivo', Filters\TextFilterType::class)
            ->add('nombre', Filters\TextFilterType::class)
        
            ->add('tipoDocumento', Filters\EntityFilterType::class, array(
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
