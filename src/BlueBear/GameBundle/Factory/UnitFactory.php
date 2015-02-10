<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Entity\UnitInstance;
use BlueBear\GameBundle\Game\EntityType;
use BlueBear\GameBundle\Game\EntityTypeAttribute;
use Exception;

/**
 * UnitFactory
 *
 * @toRemove()
 */
class UnitFactory
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
    protected $unitsModels = [];


    /**
     * Create a instance of a unit with its "pattern" on map in a specific position
     *
     * @param Context $context
     * @param Unit $unit
     * @param Position $position
     * @throws Exception
     */
    public function create(Context $context, Unit $unit, Position $position)
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

    /**
     * @return UnitManager
     */
    protected function getUnitManager()
    {
        return $this->getContainer()->get('bluebear.manager.unit');
    }

    /**
     * @return \BlueBear\GameBundle\Game\EntityType[]
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param \BlueBear\GameBundle\Game\EntityType[] $entityType
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return MapItemManager
     */
    protected function getMapItemManager()
    {
        return $this->getContainer()->get('bluebear.manager.map_item');
    }
}