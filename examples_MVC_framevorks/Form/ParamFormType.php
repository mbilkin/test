<?php
namespace Form;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParamFormType extends AbstractType
{
    private $class = 'Entity\Param';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Название', 'attr'=>array('class'=>'form-control')))
            ->add('description', null, array('label' => 'Описание', 'attr'=>array('class'=>'form-control')))
            ->add('value', null, array('label' => 'Значение', 'attr'=>array('class'=>'form-control')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'param'
        ));
    }

    public function getName()
    {
        return 'param';
    }
}