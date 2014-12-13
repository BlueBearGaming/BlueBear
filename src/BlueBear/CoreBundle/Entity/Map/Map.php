<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Sizable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
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

    const MAP_TYPE_EDITOR = 1;
    const MAP_TYPE_DEBUG = 2;
    const MAP_TYPE_GAME = 3;

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
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", mappedBy="currentMap", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="current_context", nullable=true)
     */
    protected $currentContext;

    /**
     * Map mode :
     *   > EDITOR : run in edit mode
     *   > DEBUG : run with debug information
     *   > GAME: normal run of the map
     *
     * @var int
     */
    protected $mode = self::MAP_TYPE_GAME;

    /**
     *
     */
    public function __construct()
    {
        $this->contexts = new ArrayCollection();
    }


    /**
     * @return array
     */
    public function toArray()
    {
        // export tiles to array
        $jsonTiles = [];
        $tiles = $this->getCurrentContext()->getTiles();
        /** @var Tile $tile */
        foreach ($tiles as $tile) {
            $jsonTiles[$tile->getId()] = $tile->toArray();
        }
        // export layers to array
        $jsonLayers = [];
        $layers = $this->getLayers();
        /** @var Layer $layer */
        foreach ($layers as $layer) {
            $jsonLayers[$layer->getId()] = $layer->toArray();
        }
        // export context to array
        $contextJson = null;

        if ($this->getCurrentContext()) {
            $contextJson = $this->getCurrentContext()->toArray();
        }
        $json = [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            //'tiles' => $jsonTiles,
            'layers' => $jsonLayers,
            'context' => $contextJson
        ];
        return $json;
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
     * @param Context $context
     */
    public function addContext(Context $context)
    {
        $this->contexts[] = $context;
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
        $currentContext->setCurrentMap($this);
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
}
