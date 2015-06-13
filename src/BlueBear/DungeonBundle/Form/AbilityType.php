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
                'class' => 'col-sm-2'
            ]
        ]);
        $builder->add('dexterity', 'integer');
        $builder->add('constitution', 'integer');
        $builder->add('intelligence', 'integer');
        $builder->add('wisdom', 'integer');
        $builder->add('charisma', 'integer');
        $builder->add('remaining', 'hidden');
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
