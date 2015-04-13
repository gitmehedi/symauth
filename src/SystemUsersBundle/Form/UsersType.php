<?php

namespace SystemUsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsersType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $labelAttr = array('class' => 'col-sm-2 control-label');

        $builder->add('username', 'text', array(
            'label'      => 'Username',
            'attr'       => array('class' => 'form-control', 'max-length' => 100, '' => ''),
            'label_attr' => $labelAttr,
        ));

        $builder->add('name', 'text', array(
            'label'      => 'Name',
            'attr'       => array('class' => 'form-control', 'max-length' => 100, '' => ''),
            'label_attr' => $labelAttr,
        ));

        $builder->add('email', 'email', array(
            'label'      => 'Email',
            'attr'       => array('class' => 'form-control', 'max-length' => 100, '' => ''),
            'label_attr' => $labelAttr,
        ));

   
        $builder->add('plainpassword', 'repeated', array(
           'type'=>'password',
        ));


        /* --------- All Button Types of Presentation ---------- */

        $builder->add('submit', 'submit', array(
            'label' => 'Create',
            'attr'  => array('class' => 'btn btn-success'))
        );

        $builder->add('reset', 'button', array(
            'label' => 'Cancel',
            'attr'  => array('class' => 'btn btn-default'))
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'SystemUsersBundle\Entity\Users',
            'csrf_protection' => true,
            'csrf_field_name' => '_hacktacktoom',
            'intention'       => 'new',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'systemusersbundle_users';
    }

}
