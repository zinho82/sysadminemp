<?php

namespace EmpresaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmpresaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                )
            ))
            ->add('rut',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                )
            ))
            ->add('nombreFantasia',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                )
            ))
            ->add('empresaOrigen', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Empresa',
                'choice_label' => 'nombrefantasia',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
 
            ))
            ->add('direccion',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
                'required'  =>  false
            ))
            ->add('comuna',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
                'required'  =>  false
            ))
            ->add('ciudad',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
                'required'  =>  false
            ))
            ->add('region',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                ),
                'required'  =>  false
            ))
            ->add('estadoEmpresa', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,'attr'  =>  array(
                    'class' =>  'form-control'
                ),
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Empresa'
        ));
    }
}
