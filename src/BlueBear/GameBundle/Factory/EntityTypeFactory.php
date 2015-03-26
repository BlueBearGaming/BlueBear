<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Game\EntityBehavior;
use BlueBear\GameBundle\Game\EntityType;
use BlueBear\GameBundle\Game\EntityTypeAttribute;
use Exception;

class EntityTypeFactory
{
    use ContainerTrait;

    /**
     * @var EntityTypeAttribute[]
     */
    protected $entityTypeAttributes = [];

    /**
     * @var EntityType[]
     */
    protected $entityTypes = [];

    protected $entityBehaviors = [];

    /**
     * @var EntityModel[]
     */
    protected $entityModels = [];

    /**
     * Create entity types from configuration
     *
     * @param array $entityTypesConfig
     * @param array $entityAttributesConfig
     * @param array $entityBehaviorsConfig
     * @throws Exception
     */
    public function createEntityTypes(array $entityTypesConfig, array $entityAttributesConfig, array $entityBehaviorsConfig)
    {
        // check if configuration is not empty
        if (!count($entityTypesConfig)) {
            throw new Exception('Empty entity types configuration');
        }
        if (!count($entityAttributesConfig)) {
            throw new Exception('Empty entity attribute configuration');
        }
        if (!count($entityBehaviorsConfig)) {
            throw new Exception('Empty entity behaviors configuration');
        }
        // creating available entity attribute
        foreach ($entityAttributesConfig as $name => $entityAttributeConfig) {
            $attribute = new EntityTypeAttribute();
            $attribute->setName($name);
            $attribute->setLabel($entityAttributeConfig['label']);
            $attribute->setType($entityAttributeConfig['type']);
            $this->entityTypeAttributes[$name] = $attribute;
        }
        // creating available entity behavior
        foreach ($entityBehaviorsConfig as $name => $listener) {
            $behavior = new EntityBehavior();
            $behavior->setName($name);
            $behavior->setListener($listener);
            $this->entityBehaviors[$name] = $behavior;
        }
        // creating available entity type
        foreach ($entityTypesConfig as $name => $entityTypeConfig) {
            $entityType = new EntityType();
            $entityType->setName($name);
            $entityType->setLabel($entityTypeConfig['label']);
            // adding current entity type attributes
            foreach ($entityTypeConfig['attributes'] as $attributeName) {
                if (!array_key_exists($attributeName, $this->entityTypeAttributes)) {
                    throw new Exception('Unknown entity attribute type : ' . $attributeName);
                }
                $entityType->addAttribute($this->entityTypeAttributes[$attributeName]);
            }
            // behaviors are optional for entity
            if (array_key_exists('behaviors', $entityTypeConfig)) {
                // adding current entity type behaviors
                foreach ($entityTypeConfig['behaviors'] as $behaviorName) {
                    if (!array_key_exists($behaviorName, $this->entityBehaviors)) {
                        throw new Exception('Unknown entity attribute behavior : ' . $behaviorName);
                    }
                    $entityType->addBehavior($this->entityBehaviors[$behaviorName]);
                }
            }
            $this->entityTypes[] = $entityType;
        }
    }

    /**
     * @return EntityType[]
     */
    public function getEntityTypes()
    {
        return $this->entityTypes;
    }

    /**
     * @param $entityTypeName
     * @return EntityType
     * @throws Exception
     */
    public function getEntityType($entityTypeName)
    {
        if (!array_key_exists($entityTypeName, $this->entityTypes)) {
            throw new Exception('Invalid entity tape name');
        }
        return $this->entityTypes[$entityTypeName];
    }

    /**
     * @return EntityTypeAttribute[]
     */
    public function getEntityTypeAttributes()
    {
        return $this->entityTypeAttributes;
    }

    /**
     * @param EntityTypeAttribute[] $entityTypeAttributes
     */
    public function setEntityTypeAttributes($entityTypeAttributes)
    {
        $this->entityTypeAttributes = $entityTypeAttributes;
    }

    /**
     * @return EntityBehavior[]
     */
    public function getEntityBehaviors()
    {
        return $this->entityBehaviors;
    }
}