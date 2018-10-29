<?php

namespace BancoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class RegistropagoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('total', Filters\NumberFilterType::class)
            ->add('fechaPago', Filters\DateTimeFilterType::class)
            ->add('fechaCobroCheque', Filters\DateTimeFilterType::class)
            ->add('codigoTransferencia', Filters\TextFilterType::class)
            ->add('numeroTc', Filters\TextFilterType::class)
        
            ->add('factura', Filters\EntityFilterType::class, array(
                    'class' => 'BackendBundle\Entity\Facturas',
                    'choice_label' => 'id',
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
