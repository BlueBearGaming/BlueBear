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
     * Context of the tile
     *
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", inversedBy="tiles")
     */
    protected $context;

    /**
     * Link between the tile and a pencil on a layer
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilTile", mappedBy="tile")
     * @ORM\JoinColumn(name="pencil_tiles")
     */
    protected $pencilTiles;

    /**
     * Convert the tile to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'x' => $this->getX(),
            'y' => $this->getY()
        ];
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
} 