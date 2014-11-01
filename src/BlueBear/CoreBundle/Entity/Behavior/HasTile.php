<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

trait HasTile
{
    protected $tiles = [];

    /**
     * @return mixed
     */
    public function getTiles()
    {
        return $this->tiles;
    }

    /**
     * @param mixed $tiles
     */
    public function setTiles($tiles)
    {
        $this->tiles = $tiles;
    }

    public function addTiles($tile)
    {
        $this->tiles[] = $tile;
    }
} 