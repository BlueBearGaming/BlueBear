<?php

namespace BlueBear\EngineBundle\Engine\Context;

use BlueBear\CoreBundle\Entity\Behavior\HasMapManager;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Tile;

class ContextFactory
{
    use HasMapManager;

    /**
     * Create a initial context from map data
     *
     * @param Map $map
     * @return Context
     */
    public function create(Map $map)
    {
        // create initial context
        $context = new Context();
        $context->setLabel('Initial context');
        $context->setMap($map);
        // adding context to map
        $map->getContexts()->add($context);
        // set context as current context
        $map->setCurrentContext($context);
        $this->getMapManager()->save($context);
        $this->getMapManager()->save($map);
        // on context creation, we create tiles
        $x = 0;
        $tiles = [];

        while ($x < $map->getWidth()) {
            $y = 0;

            while ($y < $map->getHeight()) {
                $tile = new Tile();
                $tile->setX($x);
                $tile->setY($y);
                $tile->setContext($context);
                $this->getMapManager()->save($tile);
                $tiles[] = $tile;
                $y++;
            }
            $x++;
        }
        $context->setTiles($tiles);

        return $context;
    }
} 