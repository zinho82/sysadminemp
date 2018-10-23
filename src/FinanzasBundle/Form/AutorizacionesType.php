<?php

namespace FinanzasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AutorizacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('fechaAutorizacion')
            ->add('comentario', CKEditorType::class,array(
                'attr'  => array(
                    'class' =>  'form-control'
                )
            ))
            ->add('estadoAutorizacion', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=56')
                                ->orderBy('u.titulo', 'asc');
                    },
                'attr'  => array(
                    'class' =>  'form-control'
                )
            )) 
//            ->add('ordenescompra', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\Ordenescompra',
//                'choice_label' => 'id',
//                'placeholder' => 'Please choose',
//                'empty_data' => null,
//                'required' => false
// 
//            )) 
//            ->add('autorizadoPor', EntityType::class, array(
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
            'data_class' => 'BackendBundle\Entity\Autorizaciones'
        ));
    }
}
