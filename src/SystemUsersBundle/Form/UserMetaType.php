<?php

namespace SystemUsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SystemUsersBundle\Entity\UserMeta;
use SystemUsersBundle\Entity\DistrictsRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserMetaType extends AbstractType
{

    public $em;
    private $presentDisctrict;
    private $permanentDistrict;
    private $presentStation;
    private $permanentStation;
    private $jobLocation;

    public function __construct($em, UserMeta $usermeta)
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $lattr = array('class' => 'col-sm-2 control-label');

        $builder->add('name', 'text', array(
            'label'      => 'Name',
            'attr'       => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'First and last name, please',),
            'label_attr' => $lattr,
        ));

        $builder->add('father_name', 'text', array(
            'label'       => 'Father Name',
            'attr'        => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'Fahter full name, please'),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('mother_name', 'text', array(
            'label'       => 'Mother Name',
            'attr'        => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'Mother full name, please'),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('date_of_birth', 'date', array(
            'label'       => 'Date of Birth',
            'attr'        => array('class' => 'form-control', 'id' => 'datepicker'),
            'label_attr'  => $lattr,
            'widget'      => 'single_text',
            'format'      => 'MM/dd/yyyy',
            'constraints' => array(new NotBlank())
        ));

        $builder->add('last_education', 'choice', array(
            'label'       => 'Last Education',
            'attr'        => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'Last finished education, please'),
            'label_attr'  => $lattr,
            'choices'     => $this->lastEducation(),
            'constraints' => array(new NotBlank())
        ));

        $builder->add('job_location', 'entity', array(
            'label'         => 'Job Location',
            'attr'          => array('class' => 'form-control selectcheck', 'id' => 'job_location'),
            'label_attr'    => $lattr,
            'class'         => 'SporshoUserBundle:Districts',
            'query_builder' => function(DistrictsRepository $er) {
        return $er->createQueryBuilder('u')
                        ->orderBy('u.fullName', 'ASC');
    },
            'property'    => 'full_name',
            'empty_value' => 'Select',
            'empty_data'  => 0,
            'data'        => $this->jobLocation,
            'constraints' => array(new NotBlank())
        ));

//        $builder->add('job_status', 'text', array(
//            'label'       => 'Job Status',
//            'attr'        => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'Preferred Job location, please'),
//            'label_attr'  => $lattr,
//            'constraints' => array(new NotBlank())
//        ));

        $builder->add('job_status', 'choice', array(
            'label'       => 'Job Status',
            'attr'        => array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'Preferred Job location, please'),
            'label_attr'  => $lattr,
            'choices'     => $this->jobStatus(),
            'constraints' => array(new NotBlank())
        ));

        $builder->add('national_id', 'text', array(
            'label'       => 'National ID',
            'attr'        => array('class' => 'form-control', 'maxlength' => 14, 'placeholder' => 'National id card no, please'),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));



        $builder->add('present_address', 'textarea', array(
            'label'       => 'Present Address',
            'attr'        => array('class' => 'form-control', 'maxlength' => 250, 'required' => false),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('present_district', 'entity', array(
            'label'         => 'District',
            'attr'          => array('class' => 'form-control selectcheck', 'id' => 'present_districts'),
            'label_attr'    => $lattr,
            'class'         => 'SporshoUserBundle:Districts',
            'query_builder' => function(DistrictsRepository $er) {
        return $er->createQueryBuilder('u')
                        ->orderBy('u.fullName', 'ASC');
    },
            'property'    => 'full_name',
            'empty_value' => 'Select',
            'empty_data'  => 0,
            'data'        => $this->presentDisctrict,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('present_police_station', 'entity', array(
            'label'       => 'Police Station',
            'attr'        => array('class' => 'form-control'),
            'label_attr'  => $lattr,
            'class'       => 'SporshoUserBundle:PoliceStation',
            'property'    => 'full_name',
            'expanded'    => false,
            'multiple'    => false,
            'empty_value' => 'Select',
            'empty_data'  => 0,
            'data'        => $this->presentStation,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('permanent_address', 'textarea', array(
            'label'       => 'Permanent Address',
            'attr'        => array('class' => 'form-control', 'maxlength' => 250),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));


        $builder->add('permanent_district', 'entity', array(
            'label'         => 'District',
            'attr'          => array('class' => 'form-control selectcheck', 'id' => 'permanent_districts'),
            'label_attr'    => $lattr,
            'class'         => 'SporshoUserBundle:Districts',
            'query_builder' => function(DistrictsRepository $er) {
        return $er->createQueryBuilder('u')
                        ->orderBy('u.fullName', 'ASC');
    },
            'property'    => 'full_name',
            'empty_value' => 'Select',
            'empty_data'  => 0,
            'data'        => $this->permanentDistrict,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('permanent_police_station', 'entity', array(
            'label'       => 'Police Station',
            'attr'        => array('class' => 'form-control'),
            'label_attr'  => $lattr,
            'class'       => 'SporshoUserBundle:PoliceStation',
            'property'    => 'full_name',
            'expanded'    => false,
            'multiple'    => false,
            'empty_value' => 'Select',
            'empty_data'  => 0,
            'data'        => $this->permanentStation,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('mobile_no', 'text', array(
            'label'       => 'Mobile No',
            'attr'        => array('class' => 'form-control phoneno number', 'maxlength' => 11, 'placeholder' => 'Bangladeshi 11 digit mobile no, please'),
            'label_attr'  => $lattr,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('image', 'file', array(
            'label'       => 'Images',
            'attr'        => array('class' => '', 'required' => null),
            'label_attr'  => $lattr,
            'data_class'  => null,
            'constraints' => array(new NotBlank())
        ));

        $builder->add('signature_image', 'file', array(
            'label'      => 'Signature Image',
            'attr'       => array('class' => ''),
            'label_attr' => $lattr,
            'data_class' => null,
            'required'   => false
        ));

        $builder->add('national_id_image', 'file', array(
            'label'      => 'National Id Image',
            'attr'       => array('class' => ''),
            'label_attr' => $lattr,
            'data_class' => null,
            'required'   => false
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
            'data_class'      => 'SystemUsersBundle\Entity\UserMeta',
            'csrf_protection' => true,
            'csrf_field_name' => '_newTokenName',
            'intention'       => 'new',
        ));
        parent::setDefaultOptions($resolver);
    }

    public function lastEducation()
    {
        return array(
            ''  => 'Select',
            '1' => 'Masters',
            '2' => 'Honours',
            '3' => 'HSC',
            '4' => 'SSC'
        );
    }

    public function jobStatus()
    {
        return array(
            ''  => 'Select',
            '1' => 'Employed',
            '2' => 'Un-employed',
            '3' => 'Self-employed',
            '4' => 'Student'
        );
    }

    public function getName()
    {
        return 'user_meta';
    }

}
