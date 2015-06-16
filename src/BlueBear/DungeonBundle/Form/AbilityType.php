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
        foreach ($options['attributes'] as $attribute) {
            $builder->add($attribute, 'integer', [
                'attr' => [
                    'class' => 'sum-operand'
                ]
            ]);
        }
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
            'rules' => UnitOfWork::CORE_RULES,
            'attributes' => [
                'strength',
                'dexterity',
                'constitution',
                'intelligence',
                'wisdom',
                'charisma',
            ]
        ]);
    }

    public function getName()
    {
        return 'ability';
    }
}
