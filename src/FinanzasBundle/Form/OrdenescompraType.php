<?php

namespace FinanzasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OrdenescompraType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
//                ->add('numeroOc', TextType::class, array(
//                    'attr' => array(
//                        'class' => 'form-control'
//                    )
//                ))
                ->add('fechaEstimadaCompra', DateType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ))
                ->add('proveedoresClientes', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\ProveedoresClientes',
                    'choice_label' => 'nombre',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('campana', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Campana',
                    'choice_label' => 'nombre',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'label' => 'Campaña / Proyecto',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('tipoOc', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=31')
                                ->orderBy('u.titulo', 'asc');
                    },
                ))
                ->add('empresa', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Empresa',
                    'choice_label' => 'nombre',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
//                ->add('estado', EntityType::class, array(
//                    'class' => 'BackendBundle\Entity\Config',
//                    'choice_label' => 'titulo',
//                    'placeholder' => 'Please choose',
//                    'empty_data' => null,
//                    'required' => true,
//                    'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
//                        return $co->createQueryBuilder('u')
//                                ->where('u.pertenece=37')
//                                ->orderBy('u.titulo', 'asc');
//                    },
//                    'attr' => array(
//                        'class' => 'form-control'
//                    )
//                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Ordenescompra'
        ));
    }

}
