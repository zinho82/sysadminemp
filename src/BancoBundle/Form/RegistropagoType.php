<?php

namespace BancoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistropagoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total', TextType::class,array(
                'attr'  => array(
                    'class' =>  'form-control',
                ),
                'required'  =>  true,
                'label' =>  'Monto'
            ))
            ->add('codigoOperacion', TextType::class,array(
                'attr'  => array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
            ))
            ->add('numeroTc', TextType::class,array(
                'attr'  => array(
                    'class' =>  'form-control',
                ),
                'required'  =>  false,
                'label' =>  'Numero Tarjeta Credito'
            ))
            ->add('tipooperacion', EntityType::class, array(
                'class' => 'BackendBundle\Entity\Config',
                'choice_label' => 'titulo',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => true,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=81')
                                ->orderBy('u.titulo', 'asc');
                    },
                            'attr'  => array(
                    'class' =>  'form-control',
                ),
                            'label' => 'Forma de Pago'
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Registropago'
        ));
    }
}
