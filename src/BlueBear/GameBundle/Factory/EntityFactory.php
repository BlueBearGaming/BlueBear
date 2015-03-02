<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\EntityInstance;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Game\EntityType;
use BlueBear\GameBundle\Game\EntityTypeAttribute;
use Exception;

class EntityFactory
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

    /**
     * @var EntityModel[]
     */
    protected $entityModels = [];

    /**
     * Create a instance of an entity model on the map at specific position
     *
     * @param Context $context
     * @param EntityModel $entityModel
     * @param Position $position
     * @param Layer $layer
     * @throws Exception
     */
    public function create(Context $context, EntityModel $entityModel, Position $position, Layer $layer)
    {
        // we try to find if an other of the same instance and same type if on same position
        $entityInstance = $this
            ->getContainer()
            ->get('bluebear.manager.entity_instance')
            ->findByTypeAndPosition($context, $entityModel->getType(), $position);
        /** @BlueBearGameRule : only one entity with the same type with same coordinates */
        if ($entityInstance) {
            throw new Exception('Unable to create entity instance. MapItem "' . $entityInstance->getMapItem()->getId() . '" has already an entity');
        }
        // create a instance from the unit pattern
        $entityInstance = new EntityInstance();
        $entityInstance->hydrateFromModel($entityModel);
        $entityInstance->setLabel('John Panda');

        // we must check if layer is allowed
        $layers = $context->getMap()->getLayers();

        if (!$layer->isAllowed($layers->toArray())) {
            throw new Exception('Request layer to put entity is not allowed');
        }
        // assign it to a map item
        $mapItem = new MapItem();
        $mapItem->setX($position->x);
        $mapItem->setY($position->y);
        $mapItem->setLayer($layer);
        $mapItem->setContext($context);
        // unit instance carry relationship
        $entityInstance->setMapItem($mapItem);
        // saving entity
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($mapItem);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * @param array $entityTypesConfig
     * @param array $entityAttributesConfig
     * @throws Exception
     */
    public function setEntityTypes(array $entityTypesConfig, array $entityAttributesConfig)
    {
        if (!count($entityTypesConfig)) {
            throw new Exception('Invalid entity types configuration');
        }
        if (!count($entityAttributesConfig)) {
            throw new Exception('Invalid entity attribute configuration');
        }
        foreach ($entityAttributesConfig as $name => $entityAttributeConfig) {
            $attribute = new EntityTypeAttribute();
            $attribute->setName($name);
            $attribute->setLabel($entityAttributeConfig['label']);
            $attribute->setType($entityAttributeConfig['type']);
            $this->entityTypeAttributes[$name] = $attribute;
        }
        foreach ($entityTypesConfig as $name => $entityTypeConfig) {
            $entityType = new EntityType();
            $entityType->setName($name);
            $entityType->setLabel($entityTypeConfig['label']);

            foreach ($entityTypeConfig['attributes'] as $attributeName) {
                if (!array_key_exists($attributeName, $this->entityTypeAttributes)) {
                    throw new Exception('Unknown entity attribute type : ' . $attributeName);
                }
                $entityType->addAttribute($this->entityTypeAttributes[$attributeName]);
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
}