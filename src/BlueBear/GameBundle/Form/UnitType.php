<?php

namespace BlueBear\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('attributes');
    }

    public function getName()
    {
        return 'unit';
    }
}