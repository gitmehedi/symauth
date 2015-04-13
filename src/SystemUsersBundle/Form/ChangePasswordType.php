<?php

namespace SystemUsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Blank;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lattr = array('class' => 'col-sm-2 control-label');
        $builder->add('plainpassword', 'password', array(
            'label'       => 'Old Password',
            'attr'        => array('class' => 'form-control'),
            'label_attr'  => $lattr,
            'constraints' => array(new Blank())
        ));
        $builder->add('resetPassword', 'repeated', array(
            'attr'            => array('class' => 'form-control', 'minlength' => 8),
            'type'            => 'password',
            'invalid_message' => 'The password fields must match.',
            'required'        => true,
            'first_options'   => array('label' => 'Password', 'attr' => array('class' => 'form-control'), 'label_attr' => $lattr),
            'second_options'  => array('label' => 'Confirm Password', 'attr' => array('class' => 'form-control'), 'label_attr' => $lattr),
        ));

        /* ----------- All Button Types of Presentation */

        $builder->add('submit', 'submit', array(
            'label' => 'Create',
            'attr'  => array('class' => 'btn btn-success')));

        $builder->add('reset', 'button', array(
            'label' => 'Cancel',
            'attr'  => array('class' => 'btn btn-default')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SystemUsersBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'change_passwd';
    }

}
