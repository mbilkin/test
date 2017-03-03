<?php
namespace Form;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScheduleFormType extends AbstractType
{
    private $class = 'Entity\Schedule';
    private $rules;
    private $showRule;
    
    public function __construct ($rules=array(),$showRule = true) {
        $this->rules = $rules;
        $this->showRule = $showRule;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title', null, array('label' => 'Название', 'attr'=>array('class'=>'form-control')))
            ->add('Start', 'time', array(
                'input'  => 'datetime',
                //'widget' => 'choice',
                'label' => 'Начало',
                'attr'=>array('class'=>'form-control'),
                'required'=>true
            ))
            ->add('Finish', 'time', array(
                'input'  => 'datetime',
                //'widget' => 'choice',
                'label' => 'Конец',
                'attr'=>array('class'=>'form-control'),
                'required'=>true
            ))
            ->add('Priority', null, array('label' => 'Приоритет', 'attr'=>array('class'=>'form-control')));
        if ($this->showRule)  
        $builder
            ->add('intRuleID', 'choice', array(
                'choices' => $this->rules,
                'label' => 'Стратегия',
                'attr'=>array('class'=>'form-control'),
                'required'=>false
            ));
        $builder
            ->add('blocked', 'checkbox', array(
                'value' => 0,
                'label' => 'Блокировка',
                'attr'=>array('class'=>'form-control', 'data-toggle'=>'switch'),
                'required'=>false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'schedule'
        ));
    }

    public function getName()
    {
        return 'schedule';
    }
}