<?php

namespace BlueBear\UserBundle\Form;

use BlueBear\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder->add('username', 'text', [
            'label' => 'form.username'
        ]);
        $builder->add('email', 'text', [
            'label' => 'form.email'
        ]);
        if (!$user->getId()) {
            $builder->add('plainPassword', 'repeated', [
                'type' => 'password',
                'first_options'  => [
                    'label' => 'form.password'
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation'
                ],
            ]);
        }
        $builder->add('roles', 'choice', [
            'choices' => [
                'ROLE_ADMIN' => 'bluebear.admin.administrator',
                'ROLE_CONTRIBUTOR' => 'bluebear.admin.contributor'
            ],
            'expanded' => true,
            'multiple' => true,
            'translation_domain' => 'messages'
        ]);
        $builder->add('enabled', 'checkbox', [
            'required' => false,
            'label' => 'bluebear.cms.enabled',
            'translation_domain' => 'messages'
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'FOSUserBundle'
        ]);

    }

    public function getName()
    {
        return 'user';
    }
}
