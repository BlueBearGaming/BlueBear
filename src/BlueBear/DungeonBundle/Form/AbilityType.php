<?php

namespace BlueBear\DungeonBundle\Form;

use BlueBear\DungeonBundle\UnitOfWork\UnitOfWork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('strength', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('dexterity', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('constitution', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('intelligence', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('wisdom', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('charisma', 'integer', [
            'attr' => [
                'class' => 'sum-operand'
            ]
        ]);
        $builder->add('remaining', 'integer', [
            'attr' => [
                'class' => 'remaining'
            ]
        ]);
        $builder->add('sum', 'hidden', [
            'attr' => [
                'class' => 'sum-result'
            ]
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'rules' => UnitOfWork::CORE_RULES
        ]);
    }

    public function getName()
    {
        return 'ability';
    }
}
