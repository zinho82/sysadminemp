<?php

namespace BancoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChequesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroCheque', TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('fechaCobro')
            ->add('registropago', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Registropago',
                'choice_label' => 'codigoTransferencia',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            )) 
            ->add('banco', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            )) 
            ->add('estado', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                'attr'  =>  array(
                    'class' =>  'form-control',
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
            'data_class' => 'BackendBundle\Entity\Cheques'
        ));
    }
}
