<?php

namespace RrhhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class RrhhType extends AbstractType
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
                    'class' =>  'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('apellidoPaterno',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('apellidoMaterno',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('rut',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('direccion',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('comuna',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('ciudad',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('region',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('sueldoBruto',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  true,
            ))
            ->add('correoElectronico',  TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('cargo', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=5')
                                ->orderBy('u.titulo', 'asc');
                    },
                             'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            ))
                            ->add('departamento', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Departamentos',
                'choice_label' => 'nombreDepartamento',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                             'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            ))
            ->add('afp', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=15')
                                ->orderBy('u.titulo', 'asc');
                    },
                             'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            )) 
            ->add('institucionSalud', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=20')
                                ->orderBy('u.titulo', 'asc');
                    },
                             'attr'  =>  array(
                    'class' =>  'form-control',
                ),
 
            )) 
            ->add('empresa', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Empresa',
                'choice_label' => 'nombre',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.estadoEmpresa=2')
                                ->orderBy('u.nombreFantasia', 'asc');
                    },
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
            'data_class' => 'BackendBundle\Entity\Rrhh'
        ));
    }
}
