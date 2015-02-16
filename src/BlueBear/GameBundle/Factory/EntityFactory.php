<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Utils\Position;
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
     * @param EntityModel $entity
     * @param Position $position
     * @throws Exception
     * @internal param Unit $unit
     */
    public function create(Context $context, EntityModel $entity, Position $position)
    {
        $unitLayer = null;
        $layers = $context->getMap()->getLayers();

        /** @var Layer $layer */
        foreach ($layers as $layer) {
            if ($layer->getType() == Constant::LAYER_TYPE_UNIT) {
                $unitLayer = $layer;
                break;
            }
        }
        if (!$unitLayer) {
            throw new Exception('Unable to create unit instance. Map "' . $context->getMap()->getId() . '" has no unit layer');
        }
        $unitInstance = $this->getUnitManager()->findInstanceByPosition($context, $position);
        /** @BlueBearGameRule : only one unit by map item */
        if ($unitInstance) {
            throw new Exception('Unable to create unit instance. MapItem "' . $unitInstance->getMapItem()->getId() . '" has already an unit');
        }
        // create a instance from the unit pattern
        $unitInstance = new UnitInstance();
        $unitInstance->hydrateFromUnit($unit);
        // assign it to the map item
        $mapItem = new MapItem();
        $mapItem->setX($position->getX());
        $mapItem->setY($position->getY());
        $mapItem->setLayer($unitLayer);
        $mapItem->setContext($context);
        // unit instance carry relationship
        $unitInstance->setMapItem($mapItem);
        // saving entity
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($mapItem);
        $entityManager->persist($unitInstance);
        $entityManager->flush();
    }

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