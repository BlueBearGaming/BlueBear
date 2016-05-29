<?php

namespace BlueBear\BackofficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class
            ])
        ;

        if ($options['add_roles_choices'] === true) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Administrator Role' => 'ROLE_ADMIN',
                        'Player Role' => 'ROLE_PLAYER',
                        'Backoffice user Role' => 'ROLE_USER',
                    ],
                    'multiple' => true,
                    'expanded' => true,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'add_roles_choices' => false
            ])
            ->setAllowedTypes('add_roles_choices', 'boolean')
        ;
    }
}
