<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('type', 'choice', [
            'choices' => Constant::getUnitsType()
        ]);
        $builder->add('attributes', 'collection', [
            'type' => 'attribute',
            'allow_add' => true,
            'widget_add_btn' => [
                'label' => 'Add attribute'
            ],
            'options' => [
            ]
        ]);
    }

    public function getName()
    {
        return 'unit';
    }
}