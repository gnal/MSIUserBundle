<?php

namespace Msi\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;

class ChangePasswordType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
            'attr' => [
                'class' => 'form-control',
            ],
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array(
                'label' => 'form.new_password',
                'attr' => [
                    'class' => 'form-control',
                ],
            ),
            'second_options' => array(
                'label' => 'form.new_password_confirmation',
                'attr' => [
                    'class' => 'form-control',
                ],
            ),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    public function getName()
    {
        return 'msi_user_change_password';
    }
}
