<?php

namespace BlueBear\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EntityInstanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('type', 'choice', [
            'choices' => []
        ]);
        $builder->add('attributes', 'collection', [
            'type' => 'entity_instance_attribute',
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
        return 'entity_instance';
    }
}