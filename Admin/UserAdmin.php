<?php

namespace Msi\Bundle\UserBundle\Admin;

use Msi\Bundle\CmfBundle\Admin\Admin;
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

        $roles = array();
        $roles['ROLE_SUPER_ADMIN'] = 'super admin';
        $roles['ROLE_ADMIN'] = 'admin';

        foreach ($this->container->getParameter('msi_cmf.admin_ids') as $id) {
            // why not:
            // $label = $this->container->get($id)->getLabel(1);
            $label = preg_replace(array('#^[a-z]+_[a-z]+_#', '@_admin@', '@_@'), array('', '', ' '), $id);
            $roles['ROLE_'.strtoupper($id).'_CREATE'] = $label.' | create';
            $roles['ROLE_'.strtoupper($id).'_READ'] = $label.' | read';
            $roles['ROLE_'.strtoupper($id).'_UPDATE'] = $label.' | update';
            $roles['ROLE_'.strtoupper($id).'_DELETE'] = $label.' | delete';
        }

        $builder
            ->add('roles', 'choice', array(
                'choices' => $roles,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
        ;
    }
}
