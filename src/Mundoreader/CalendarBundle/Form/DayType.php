<?php

namespace Mundoreader\CalendarBundle\Form;

use Mundoreader\CalendarBundle\Entity\User;
use Mundoreader\CalendarBundle\Entity\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;

class DayType extends AbstractType
{
    protected $calendar;

    public function __construct($calendar){
        $this->calendar = $calendar;
    }
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('gift', new GiftType(), array(
                'label' => 'Regalo'
            ));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();

                $formOptions = array(
                    'class' => 'Mundoreader\CalendarBundle\Entity\User',
                    'property' => 'email',
                    'label' => 'Quién cumple',
                    'query_builder' => function(UserRepository $er) {
                            return $er->createQueryBuilder('u')->where('u.calendar = ?1')->setParameter(1, $this->calendar);
                            //return $er->createQueryBuilder('u');
                        },
                );
                $form->add('user', 'entity', $formOptions);
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mundoreader\CalendarBundle\Entity\Day'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mundoreader_calendarbundle_day';
    }
}
