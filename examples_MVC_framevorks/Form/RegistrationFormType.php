<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Form;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    private $class = 'Entity\User';
    private $clients;
    private $needPswd;
    private $new;

    public function __construct ($clients=array(), $new=true, $needPswd=true) {
        $this->clients = $clients;
        $this->needPswd = $needPswd;
        $this->new = $new;
        if ($this->new)
             $this->needPswd=true;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->new)
        $builder
            ->add('username', null, array('label' => 'Login', 'attr'=>array('class'=>'form-control')));
        $builder
            ->add('email', 'email', array('label' => 'email', 'attr'=>array('class'=>'form-control')));
        if ($this->needPswd)
        $builder    
            ->add('password', 'repeated', array(
                'type' => 'password',
                'options' => array(),
                'first_options' => array('label' => 'form.password', 'attr'=>array('class'=>'form-control')),
                'second_options' => array('label' => 'form.password_confirmation', 'attr'=>array('class'=>'form-control')),
                'invalid_message' => 'user.password.mismatch',
            ));
        $builder
            ->add('Logins', 'choice', array(
                'choices' => $this->clients,
                'multiple' => true,
                'label' => 'Clients',
                'attr'=>array('class'=>'form-control'),
                'required'=>false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
            'validation_groups' => array('Registration'),
        ));
    }

    public function getName()
    {
        return 'user_registration';
    }
}