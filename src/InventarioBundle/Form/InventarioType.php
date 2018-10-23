<?php

namespace InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class InventarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                 ->add('cantidad', TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                )
            ))
            ->add('nombreProducto', TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                )
            ))
            ->add('descripcion', CKEditorType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                )
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
                 'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=51')
                                ->orderBy('u.titulo', 'asc');
                    },
 
            )) 
            ->add('empresa', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Empresa',
                'choice_label' => 'nombre',
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
            'data_class' => 'BackendBundle\Entity\Inventario'
        ));
    }
}
