<?php

namespace BlueBear\EngineBundle\Path;


use BlueBear\CoreBundle\Utils\Position;

class PathFinder
{
    public function find($mapItems, Position $source, Position $target, $movement)
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
