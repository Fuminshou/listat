<?php

namespace ListatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface; //use OptionResolver

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('startDate', 'date', array('data' => new \DateTime()));
        $builder->add('reset', 'reset', array('label' => 'Reset'));
        $builder->add('save', 'submit', array('label' => 'Create Project'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ListatBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'project';
    }
}
