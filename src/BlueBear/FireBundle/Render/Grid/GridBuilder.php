<?php

namespace BlueBear\FireBundle\Render\Grid;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\FireBundle\Render\Cell\Cell;
use BlueBear\FireBundle\Render\Fire\Fire;
use BlueBear\FireBundle\Render\Fireman\Fireman;

class GridBuilder
{
    /**
     * @var Map
     */
    protected $map;

    /**
     * GridBuilder constructor.
     * 
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    public function build()
    {
        $cells = [];
        $firemen = [];

        $groundLayer = $this
            ->map
            ->getLayerByName('ground_layer');

        foreach ($groundLayer->getMapItems() as $mapItem) {

            if (!array_key_exists($mapItem->getX(), $cells)) {
                $cells[$mapItem->getX()] = [];
            }
            $cells[$mapItem->getX()][$mapItem->getY()] = new Cell($mapItem->getX(), $mapItem->getY());
        }

        $unitLayer = $this
            ->map
            ->getLayerByName('fireman_layer');

        foreach ($unitLayer->getMapItems() as $mapItem) {
            $firemen = new Fireman($mapItem->getX(), $mapItem->getY(), $mapItem->getId());
        }

        return new Grid($cells, $firemen, $this->getFires(), $this->map->getContexts()->last()->getId());
    }

    protected function getFireman()
    {
        return new Fireman(0, 0);
    }

    protected function getFires()
    {
        $fires[4][4] = new Fire(4, 4);

        return $fires;
    }
}
