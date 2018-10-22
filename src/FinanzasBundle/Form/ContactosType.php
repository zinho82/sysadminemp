<?php

namespace FinanzasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
class ContactosType extends AbstractType
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
                    'class' =>  'form-control'
                )
            ))
            ->add('telefono', TextType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                )
            ))
            ->add('correo', EmailType::class,array(
                'attr'  =>  array(
                    'class' =>  'form-control'
                )
            ))
            ->add('cargo', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=5')
                                ->orderBy('u.titulo', 'asc');
                    },
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre del Documento',
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
            'data_class' => 'BackendBundle\Entity\Contactos'
        ));
    }
}
