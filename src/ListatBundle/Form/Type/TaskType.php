<?php

namespace ListatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface; //use OptionResolver

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('startDate', 'date', array('placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day')));
        $builder->add('save', 'submit', array('label' => 'Save Task'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ListatBundle\Entity\Task'
        ));
    }

    public function getName()
    {
        return 'task';
    }
}
