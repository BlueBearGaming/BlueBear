<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\GameBundle\Game\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('type', 'choice', [
            'choices' => $this->sortEntityTypes($options['entity_types'])
        ]);
        //var_dump($options['data']);
//        $builder->add('attributes', 'collection', [
//            'type' => 'entity_model_attribute',
//            'allow_add' => true,
//            'widget_add_btn' => [
//                'label' => 'Add attribute'
//            ],
//            'options' => [
//
//            ]
//        ]);
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
        return 'entity_model';
    }

    protected function sortEntityTypes(array $entityTypes)
    {
        $sorted = [];
        /** @var EntityType $entityType */
        foreach ($entityTypes as $entityType) {
            $sorted[$entityType->getName()] = $entityType;
        }
        return $entityTypes;
    }
}