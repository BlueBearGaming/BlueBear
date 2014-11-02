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
} 