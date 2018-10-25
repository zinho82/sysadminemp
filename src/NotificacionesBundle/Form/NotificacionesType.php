<?php

namespace NotificacionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NotificacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', CKEditorType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  => true,
            ))
            ->add('area', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Departamentos',
                'choice_label' => 'nombreDepartamento',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                 'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'label' => 'Departamentos',
 
            )) 
                
//            ->add('usuario', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\Usuario',
//                'choice_label' => 'username',
//                'placeholder' => 'Please choose',
//                'empty_data' => null,
//                'required' => false
// 
//            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Notificaciones'
        ));
    }
}
