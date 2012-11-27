<?php

namespace Msi\Bundle\UserBundle\Admin;

use Msi\Bundle\CmfBundle\Admin\Admin;
use Msi\Bundle\CmfBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class GroupAdmin extends Admin
{
    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('name')
            ->add('', 'action')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
        ;

        $roles = array();
        $roles['ROLE_SUPER_ADMIN'] = 'super admin';
        $roles['ROLE_ADMIN'] = 'admin';

        foreach ($this->container->getParameter('msi_cmf.admin_ids') as $id) {
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
