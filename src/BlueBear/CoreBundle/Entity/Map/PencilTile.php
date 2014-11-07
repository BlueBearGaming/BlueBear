<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * PencilTile
 *
 * Represents a link between a pencil and tile. A tile has a pencil on one specific layer
 *
 * @ORM\Table(name="pencil_tile")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\PencilTileRepository")
 */
class PencilTile
{
    use Id;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil")
     */
    protected $pencil;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Tile")
     */
    protected $tile;

    /**
     * @return mixed
     */
    public function getPencil()
    {
        return $this->pencil;
    }

    /**
     * @param mixed $pencil
     */
    public function setPencil($pencil)
    {
        $this->pencil = $pencil;
    }

    /**
     * @return mixed
     */
    public function getTile()
    {
        return $this->tile;
    }

    /**
     * @param mixed $tile
     */
    public function setTile($tile)
    {
        $this->tile = $tile;
    }
} 