<?php

namespace Msi\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileType extends BaseType
{
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getName()
    {
        return 'msi_user_profile';
    }
}
