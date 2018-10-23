<?php

namespace FinanzasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ItemsOcType extends AbstractType
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
                ->add('inventario', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Inventario',
                'choice_label' => 'nombreProducto',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'label' =>  'Producto / Herramienta',
                    'attr'  =>  array(
                        'class' =>  'form-control',
                    )
//                    'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
//                        return $co->createQueryBuilder('u')
//                                ->where('u.empresa=37')
//                                ->orderBy('u.titulo', 'asc');
//                    },
 
            )) 
            ->add('descripcion', CKEditorType::class,array(
                'attr'  =>  array(
                        'class' =>  'form-control',
                    )
            ))
            ->add('valor', TextType::class,array(
                'attr'  =>  array(
                        'class' =>  'form-control',
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
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\ItemsOc'
        ));
    }
}
