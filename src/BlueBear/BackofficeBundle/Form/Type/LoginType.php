<?php

namespace BlueBear\BackofficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'Login...'
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Password...'
                ]
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'login';
    }
}

