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
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", cascade={"persist"})
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

    protected $currentContext;

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

    /**
     * @return mixed
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @param mixed $contexts
     */
    public function setContexts($contexts)
    {
        $this->contexts = $contexts;
    }

    /**
     * @return Context
     */
    public function getCurrentContext()
    {
        return $this->currentContext;
    }

    /**
     * @param mixed $currentContext
     */
    public function setCurrentContext(Context $currentContext)
    {
        $this->currentContext = $currentContext;
    }

    public function toJson()
    {
        // export tiles to array
        $jsonTiles = [];
        $tiles = $this->getTiles();
        /** @var Tile $tile */
        foreach ($tiles as $tile) {
            $jsonTiles[$tile->getId()] = $tile->toJson();
        }
        // export layers to array
        $jsonLayers = [];
        $layers = $this->getLayers();
        /** @var Layer $layer */
        foreach ($layers as $layer) {
            $jsonLayers[$layer->getId()] = $layer->toJson();
        }
        // export context to array
        $contextJson = null;

        if ($this->getCurrentContext()) {
            $contextJson = $this->getCurrentContext()->toJson();
        }
        $json = [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'tiles' => $jsonTiles,
            'layers' => $jsonLayers,
            'context' => $contextJson
        ];
        return $json;
    }
}
