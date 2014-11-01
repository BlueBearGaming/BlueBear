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

    //protected
} 