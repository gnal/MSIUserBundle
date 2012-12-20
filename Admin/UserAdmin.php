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
            'form_template' => 'MsiUserBundle:User:form.html.twig',
        );
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('enabled', 'boolean')
            ->add('locked', 'boolean', array(
                'label' => 'Banned',
                'icon_true' => 'icon-ban-circle',
                'icon_false' => 'icon-ban-circle',
                'badge_true' => 'badge-important',
            ))
            ->add('username')
            ->add('email')
            ->add('lastLogin', 'date')
            ->add('', 'action')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('enabled')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm Password'),
            ))
            ->add('locked', 'checkbox', array(
                'label' => 'Banned',
            ))
        ;

        if ($this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || $this->container->get('security.context')->isGranted('ROLE_MSI_USER_GROUP_ADMIN_UPDATE')) {
            $builder->add('groups', 'entity', array(
                'class' => 'MsiUserBundle:Group',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ));
        }

        $roles = array();
        $roles['ROLE_SUPER_ADMIN'] = 'super admin';
        $roles['ROLE_ADMIN'] = 'admin';

        foreach ($this->container->getParameter('msi_cmf.admin_ids') as $id) {
            $label = $this->container->get($id)->getLabel(1, 'en');
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
