<?php

namespace RrhhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',  TextType::class,array(
                'attr'  =>  array(
                    'class' => 'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('password', PasswordType::class,array(
                'attr'  =>  array(
                    'class' => 'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('role',  TextType::class,array(
                'attr'  =>  array(
                    'class' => 'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('rrhh', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Rrhh',
                'choice_label' => 'nombre',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                 'attr'  =>  array(
                    'class' => 'form-control',
                ),
                'label'  =>  'Nombre Trabajador',
                
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Usuario'
        ));
    }
}
