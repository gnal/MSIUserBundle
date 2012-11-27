<?php

namespace Msi\Bundle\UserBundle\Admin;

use Msi\Bundle\CmfBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class UserAdmin extends Admin
{
    public function configure()
    {
        $this->options = array(
            'search_fields' => array('a.username', 'a.email'),
        );
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('lastLogin', 'date')
            ->add('', 'action')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('username')
            ->add('email')
        ;

        if ($this->getAction() !== 'edit') {
            $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm Password'),
            ));
        }

        if ($this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || $this->container->get('security.context')->isGranted('ROLE_MSI_USER_GROUP_ADMIN_UPDATE')) {
            $builder->add('groups', 'entity', array(
                'class' => 'MsiCmfBundle:Group',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ));
        }
    }
}
