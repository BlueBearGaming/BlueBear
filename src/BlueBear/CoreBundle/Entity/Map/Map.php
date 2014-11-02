<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Sizable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * The map.
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\MapRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Map
{
    use Id, Nameable, Label, Sizable, Timestampable, Typeable;

    /**
     * Map pencil sets
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", cascade={"persist"}, mappedBy="maps")
     */
    protected $pencilSets;

    /**
     * Map layers
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer", cascade={"persist"})
     */
    protected $layers;

    /**
     * Map contexts (ie snapshot of map state)
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", mappedBy="map", cascade={"persist", "remove"})
     */
    protected $contexts;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Tile", mappedBy="map", cascade={"persist", "remove"})
     */
    protected $tiles;

    /**
     * Initialize the map and its context
     */
    public function __construct()
    {
        // by default a map has always a context
        $this->context = new Context();
    }

    /**
     * @return mixed
     */
    public function getPencilSets()
    {
        return $this->pencilSets;
    }

    /**
     * @param array $pencilSets
     */
    public function setPencilSets($pencilSets)
    {
        $this->pencilSets = $pencilSets;
    }

    /**
     * @return mixed
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * @param mixed $layers
     */
    public function setLayers($layers)
    {
        $this->layers = $layers;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }
}
