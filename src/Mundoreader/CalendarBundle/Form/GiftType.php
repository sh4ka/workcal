<?php

namespace Mundoreader\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GiftType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Nombre'
            ))
            ->add('link', null, array(
                'label' => 'Enlace'
            ))
            ->add('price', null, array(
                'label' => 'Precio'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mundoreader\CalendarBundle\Entity\Gift'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mundoreader_calendarbundle_gift';
    }
}
