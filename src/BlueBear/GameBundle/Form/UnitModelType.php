<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnitModelType extends AbstractType
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
            'type' => 'unit_model_attribute',
            'allow_add' => true,
            'widget_add_btn' => [
                'label' => 'Add attribute'
            ],
            'options' => [
                'entity_types' => $options['entity_types']
            ]
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\GameBundle\Entity\EntityModel',
            'entity_types' => []
        ]);
    }

    public function getName()
    {
        return 'unit_model';
    }
}