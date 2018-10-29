<?php

namespace EmpresaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class CampanaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                 ->add('nombre', TextType::class,array(
                 'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                     'label'    =>'Nombre Campaña / Proyecto'
            ))
            ->add('descripcion', CKEditorType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                   
                ),
              
            ))
                 ->add('proveedoresClientes', EntityType::class, array(
                'class' => 'BackendBundle\Entity\ProveedoresClientes',
                'choice_label' => 'nombre',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                 'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'label' =>  'Proveedor',
                      'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.proveedorCliente=36')
                                ->orderBy('u.nombre', 'asc');
                    },
 
            )) 
           
            ->add('fechaFacturacion', DateType::class,array(
                 'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                  'data'  =>  new \DateTime,
            ))
            ->add('fechaInicio', DateType::class,array(
                 'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                 'data'  =>  new \DateTime,
            ))
            ->add('fechaTermino',DateType::class,array(
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
            'data_class' => 'BackendBundle\Entity\Campana'
        ));
    }
}
