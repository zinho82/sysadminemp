<?php

namespace FinanzasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FacturasType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('fecha', DateType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                    'label' => 'Fecha Factura',
                ))
                ->add('numeroFactura', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                    'label' => 'Numero de Factura',
                    'required' => true,
                ))
                ->add('campana', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Campana',
                    'choice_label' => 'descripcion',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => false
                ))
                ->add('fechaPago')
                ->add('estadoPago', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=26')
                                ->orderBy('u.titulo', 'asc');
                    },
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('departamento', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Departamentos',
                    'choice_label' => 'nombreDepartamento',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('neto', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
//            ->add('empresa', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\Empresa',
//                'choice_label' => 'nombre',
//                'placeholder' => 'Please choose',
//                'empty_data' => null,
//                'required' => false
// 
//            )) 
//            ->add('ordenescompra', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\Ordenescompra',
//                'choice_label' => 'id',
//                'placeholder' => 'Please choose',
//                'empty_data' => null,
//                'required' => false
// 
//            )) 
//            ->add('proveedoresClientes', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\ProveedoresClientes',
//                'choice_label' => 'nombre',
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
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Facturas'
        ));
    }

}
