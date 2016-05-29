<?php

namespace BlueBear\CoreBundle\Path;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Utils\Position;


class PathUtils
{
    /**
     * Find the nearest neighbours to a source position with a movement.
     *
     * @param Position $position
     * @param Map $map
     * @param int $movement
     * @return MapItem[]
     */
    public function getNearestNeighbour(Position $position, Map $map, $movement = 1)
    {
        $mapItems = $map
            ->getCurrentContext()
            ->getMapItems();
        $positions = [];
        $sortedMapItems = [];

        foreach ($mapItems as $index => $mapItem) {
            $positions[$mapItem->getX()][$mapItem->getY()] = true;
            $sortedMapItems[$mapItem->getX()][$mapItem->getY()] = $mapItem;
        }
        // find an area from the lowest point to the highest
        $lowestPoint = new Position(
            $position->x - $movement,
            $position->y - $movement
        );
        $highestPoint = new Position(
            $position->x + $movement,
            $position->y + $movement
        );
        $neighbours = [];

        while ($lowestPoint->x <= $highestPoint->x) {
            $lowestPoint->y = $position->y - $movement;

            while ($lowestPoint->y <= $highestPoint->y) {
                // the position must exists on the map
                $isXExists = array_key_exists($lowestPoint->x, $positions);
                $isYExists = $isXExists && array_key_exists($lowestPoint->y, $positions[$lowestPoint->x]);
                // we dot not add the source
                $isNotSource = $lowestPoint->x != $position->x || $lowestPoint->y != $position->y;

                if ($isXExists && $isYExists && $isNotSource) {
                    $neighbours[] = $sortedMapItems[$lowestPoint->x][$lowestPoint->y];
                }

                $lowestPoint->y++;
            }

            $lowestPoint->x++;
        }

        return $neighbours;
    }
}
