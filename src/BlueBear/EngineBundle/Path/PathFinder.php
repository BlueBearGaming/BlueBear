<?php

namespace BlueBear\EngineBundle\Path;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Utils\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class PathFinder
 *
 * Utilities to help path finding and unit movement
 */
class PathFinder
{
    /**
     * Indexed map items to make search easier
     *
     * @var array
     */
    protected $indexedMapItems = [];

    /**
     * Keep track of processed item to not process them twice
     *
     * @var array
     */
    protected $processedItems = [];

    /**
     * Found map item
     *
     * @var array
     */
    protected $foundItems = [];

    /**
     * Find available map items for moving entity instance
     *
     * @param Context $context
     * @param Position $source
     * @param $movement
     * @return MapItem[]
     */
    public function findAvailable(Context $context, Position $source, $movement)
    {
        $movement = (int)$movement;
        /** @var PersistentCollection $mapItems */
        $mapItems = $context->getMapItems()->filter(function (MapItem $mapItem) {
            // return only land map items
            return $mapItem->getLayer()->getType() == Constant::LAYER_TYPE_LAND;
        });
        /** @var MapItem $mapItem */
        foreach ($mapItems as $mapItem) {
            $this->indexedMapItems[$mapItem->getX()][$mapItem->getY()] = $mapItem;
        }
        $this->recursiveFind($source, $movement, $context->getMap()->getType());

        return new ArrayCollection($this->foundItems);
    }

    /**
     * @param PersistentCollection $mapItems
     * @param Position $source
     * @param Position $target
     * @return array
     */
    public function findPath($mapItems, Position $source, Position $target)
    {
//        $mapItemSource = $mapItems->filter(function (MapItem $mapItem) use ($source) {
//            return $mapItem->getX() == $source->x && $mapItem->getY() == $source->y;
//        });
//        $mapItemTarget = $mapItems->filter(function (MapItem $mapItem) use ($target) {
//            return $mapItem->getX() == $target->x && $mapItem->getY() == $target->y;
//        });
        // TODO @WIP
        return [
            $source,
            $target
        ];
    }

    /**
     * Find recursively available map items for a given movement
     *
     * @param Position $source
     * @param $movementLeft
     * @param $mapType
     */
    protected function recursiveFind(Position $source, $movementLeft, $mapType)
    {
        $movementLeft--;
        // find map item neighbours
        $neighbours = $this->findNeighbours($source, $mapType);
        /** @var MapItem $neighbour */
        foreach ($neighbours as $neighbour) {
            if (!isset($this->processedItems[$neighbour->getX()]) ||
                (isset($this->processedItems[$neighbour->getX()]) &&
                    !isset($this->processedItems[$neighbour->getX()][$neighbour->getY()]))
            ) {
                // if map item is not already processed we add it to the process item list
                $this->processedItems[$neighbour->getX()][$neighbour->getY()] = $neighbour;
                $this->foundItems[] = $neighbour;
            }
            if ($movementLeft > 0) {
                $this->recursiveFind($neighbour->getPosition(), $movementLeft, $mapType);
            }
        }
    }


    /**
     * Return map item neighbours according to map type
     *
     * @param Position $source
     * @param $mapType
     * @return MapItem[]
     */
    protected function findNeighbours(Position $source, $mapType)
    {
        $neighbours = [];

        // left
        if ($this->mapItemExists($position = new Position($source->x - 1, $source->y))) {
            $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
        }
        // right
        if ($this->mapItemExists($position = new Position($source->x + 1, $source->y))) {
            $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
        }
        // top
        if ($this->mapItemExists($position = new Position($source->x, $source->y - 1))) {
            $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
        }
        // bottom
        if ($this->mapItemExists($position = new Position($source->x, $source->y + 1))) {
            $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
        }
        // two additional neighbours for hexagon map
        if ($mapType == 'hexagonal') {
            if ($this->mapItemExists($position = new Position($source->x + 1, $source->y - 1))) {
                $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
            }
            if ($this->mapItemExists($position = new Position($source->x + 1, $source->y + 1))) {
                $neighbours[] = $this->indexedMapItems[$position->x][$position->y];
            }
        }
        return $neighbours;
    }

    /**
     * Return true if the map item exists in indexed map items collection
     *
     * @param Position $position
     * @return bool
     */
    protected function mapItemExists(Position $position)
    {
        return isset($this->indexedMapItems[$position->x]) && isset($this->indexedMapItems[$position->x][$position->y]);
    }

    protected function findAlgorithm(MapItem $source, $target)
    {
        // TODO in progress
        $frontier = new \SplPriorityQueue();
        $frontier->insert($source, 0);

        $cameFrom = [];
        $costSoFar = [];
        $cameFrom[$source->getId()] = null;
        $costSoFar[$source->getId()] = null;

        while (!$frontier->isEmpty()) {
            $current = $frontier->current();

            if ($current == $target) {
                break;
            }

        }
    }
}
