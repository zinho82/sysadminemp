<?php

namespace DocumentosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocumentosType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('archivo', FileType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Seleccione un archivo',
                    ),
                    'required' => true,
                ))
                ->add('nombre', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre del Documento',
                    ),
                    'required' => true,
                ))
                ->add('tipoDocumento', EntityType::class, array(
                    'class' => 'BackendBundle\Entity\Config',
                    'choice_label' => 'titulo',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true,
                    'required' => true,
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $co) {
                        return $co->createQueryBuilder('u')
                                ->where('u.pertenece=37')
                                ->orderBy('u.titulo', 'asc');
                    },
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre del Documento',
                    ),
                ))
//                ->add('empresa', EntityType::class, array(
//                    'class' => 'BackendBundle\Entity\Empresa',
//                    'choice_label' => 'nombre',
//                    'placeholder' => 'Please choose',
//                    'empty_data' => null,
//                    'required' => false
//                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Documentos'
        ));
    }

}
