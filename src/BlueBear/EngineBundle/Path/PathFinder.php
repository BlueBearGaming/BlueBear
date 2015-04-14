<?php

namespace BlueBear\EngineBundle\Path;


use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Utils\Position;

class PathFinder
{
    public function find($mapItems, Position $source, Position $target, $movement)
    {
        $mapItemSource = null;

        if (!$target) {
            /** @var MapItem $mapItem */
            foreach ($mapItems as $mapItem) {
                if ($mapItem->getX() == $source->x &&
                    $mapItem->getY() == $source->y) {
                    $mapItemSource = $source;
                }
            }
        }
        return $mapItemSource;
    }

    protected function findAlgorithm($source, $target)
    {
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
