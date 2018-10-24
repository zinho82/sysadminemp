<?php

namespace IncidenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class IncidenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaIncidencia', DateTimeType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('titulo', TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('descripcionError', CKEditorType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('fechaSolucion', DateTimeType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('solucion', CKEditorType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
            ))
            ->add('archivo', FileType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control',
                ),
                'required'  => false,
            ))
//            ->add('ingresasoPor', EntityType::class, array(
//                'class' => 'BackendBundle\Entity\Usuario',
//                'choice_label' => 'username',
//                'placeholder' => 'Please choose',
//                'empty_data' => null,
//                'required' => false
// 
//            )) 
            ->add('estado', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=65')
                                ->orderBy('u.titulo', 'asc');
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
            'data_class' => 'BackendBundle\Entity\Incidencia'
        ));
    }
}
