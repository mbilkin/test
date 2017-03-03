<?php
namespace Form;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RuleFormType extends AbstractType
{
    private $class = 'Entity\Rule';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Название', 'attr'=>array('class'=>'form-control')))
            ->add('description', null, array('label' => 'Описание', 'attr'=>array('class'=>'form-control')))
            ->add('formula', 'textarea', array('label' => 'Формула', 'attr'=>array('class'=>'form-control formula')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'rule'
        ));
    }

    public function getName()
    {
        return 'rule';
    }
}