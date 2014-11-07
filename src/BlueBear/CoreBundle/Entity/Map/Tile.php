<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Positionable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Map tile
 *
 * @ORM\Table(name="tile")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\TileRepository")
 */
class Tile
{
    use Id, Positionable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Map", inversedBy="tiles")
     */
    protected $map;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilTile", mappedBy="tile")
     * @ORM\JoinColumn(name="pencil_tile")
     */
    protected $pencilTile;

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getPencilTile()
    {
        return $this->pencilTile;
    }

    /**
     * @param mixed $pencilTile
     */
    public function setPencilTile($pencilTile)
    {
        $this->pencilTile = $pencilTile;
    }

    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'x' => $this->getX(),
            'y' => $this->getY()
        ];
    }
} 