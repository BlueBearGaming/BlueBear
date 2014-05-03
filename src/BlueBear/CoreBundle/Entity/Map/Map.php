<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use Doctrine\ORM\Mapping as ORM;

/**
 * The map.
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\MapRepository")
 */
class Map
{
    use Id, Nameable;

    /**
     * Map pencil set
     *
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet")
     */
    protected $pencilSet;
}
