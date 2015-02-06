<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\GameBundle\Entity\MapItem;
use BlueBear\GameBundle\Entity\Unit;
use BlueBear\GameBundle\Entity\UnitInstance;
use Exception;

class UnitFactory
{
    use ContainerTrait;

    /**
     * Create a instance of a unit with its "pattern" on map in a specific position
     *
     * @param Map $map
     * @param Unit $unit
     * @param Position $position
     * @throws Exception
     */
    public function create(Map $map, Unit $unit, Position $position)
    {
        $unitLayer = null;
        $layers = $map->getLayers();

        /** @var Layer $layer */
        foreach ($layers as $layer) {
            if ($layer->getType() == Constant::LAYER_TYPE_UNIT) {
                $unitLayer = $layer;
                break;
            }
        }
        if (!$unitLayer) {
            throw new Exception('Unable to create unit instance. Map "' . $map->getId() . '" has no unit layer');
        }
        /** @var MapItem $mapItem */
        $mapItem = $this->getMapItemManager()->findByPositionAndLayer($position, $unitLayer);
        /** @BlueBearGameRule : only one unit by map item */
        if ($mapItem and $mapItem->hasUnit()) {
            throw new Exception('Unable to create unit instance. MapItem "' . $mapItem->getId() . '" has already an unit');
        }
        if (!$mapItem) {
            // create a instance from the unit pattern
            $unitInstance = new UnitInstance();
            $unitInstance->hydrateFromUnit($unit);
            // assign it ti the map item
            $mapItem = new MapItem();
            $mapItem->setX($position->getX());
            $mapItem->setY($position->getY());
            $mapItem->setUnit($unitInstance);
            $this->getContainer()->get('doctrine')->getManager()->persist($mapItem);
        }
    }

    /**
     * @return MapItemManager
     */
    protected function getMapItemManager()
    {
        return $this->getContainer()->get('bluebear.manager.map_item');
    }
}