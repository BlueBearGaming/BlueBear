<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapRepository;
use BlueBear\CoreBundle\Entity\Map\Tile;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class MapManager
{
    use ManagerBehavior;

    /**
     * Find a map with its linked objects
     *
     * @param $id
     * @return Map|null
     */
    public function findMap($id)
    {
        return $this
            ->getRepository()
            ->findMap($id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save map and create linked tiles if required
     *
     * @param Map $map
     */
    public function saveMap(Map $map)
    {
        $tiles = $map->getTiles();

        // on map saving, we create tiles if they do not exist
        if (!count($tiles)) {
            $x = 0;
            $y = 0;
            $tiles = [];

            while ($x < $map->getWidth()) {
                while ($y < $map->getHeight()) {
                    $tile = new Tile();
                    $tile->setX($x);
                    $tile->setY($y);
                    $tile->setMap($map);
                    $tiles[] = $tile;
                    $y++;
                }
                $x++;
            }
            $map->setTiles($tiles);
        }
        $this->save($map);
    }

    /**
     * Return layers repository
     *
     * @return MapRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\Map');
    }
}