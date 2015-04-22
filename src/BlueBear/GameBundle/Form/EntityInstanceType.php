<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\GameBundle\Entity\EntityInstance;
use BlueBear\GameBundle\Factory\EntityTypeFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EntityInstanceType extends AbstractType
{
    /**
     * @var EntityTypeFactory
     */
    protected $entityTypeFactory;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityInstance $entityInstance */
        $entityInstance = $options['data'];

        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('pencil');
        // type cannot by changed after creation, attributes are available only after the type is chosen
        if ($entityInstance->getId()) {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
                'read_only' => true,
                'disabled' => 'disabled',
            ]);
            $builder->add('behaviors', 'choice', [
                'choices' => $this->getSortedEntityBehaviors(),
                'read_only' => true,
                'multiple' => true,
                'expanded' => true,
            ]);
        } else {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
            ]);
        }
        $builder->add('allowedLayerTypes', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'multiple' => true,
            'expanded' => true,
            'horizontal_input_wrapper_class' => 'col-sm-9 form-inline-checkboxes',
        ]);
        if ($entityInstance->getId()) {
            $builder->add('attributes', 'attribute_collection', [
                'type' => 'entity_instance_attribute'
            ]);
        }
    }

    public function getName()
    {
        return 'entity_instance';
    }

    public function setEntityTypeFactory(EntityTypeFactory $entityTypeFactory)
    {
        $this->entityTypeFactory = $entityTypeFactory;
    }

    protected function getSortedEntityTypes()
    {
        $sorted = [];
        $entityTypes = $this->entityTypeFactory->getEntityTypes();

        /** @var EntityType $entityType */
        foreach ($entityTypes as $entityType) {
            $sorted[$entityType->getName()] = $entityType;
        }
        return $entityTypes;
    }

    protected function getSortedEntityBehaviors()
    {
        $sorted = [];
        $behaviors = $this->entityTypeFactory->getEntityBehaviors();

        foreach ($behaviors as $behavior) {
            $sorted[$behavior->getName()] = ucfirst($behavior->getName());
        }
        return $sorted;
    }
}
