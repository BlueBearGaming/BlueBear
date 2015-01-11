<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessorOrder;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * The map
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\MapRepository")
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 * @AccessorOrder("custom", custom={"id", "name", "label", "type", "cellSize", "layers", "pencilSets"})
 */
class Map
{
    use Id, Nameable, Label, Timestampable, Typeable;

    const MAP_TYPE_EDITOR = 1;
    const MAP_TYPE_DEBUG = 2;
    const MAP_TYPE_GAME = 3;

    /**
     * Map pencil sets
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", cascade={"persist"})
     * @Expose()
     * @SerializedName("pencilSets")
     */
    protected $pencilSets;

    /**
     * Map layers
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer", cascade={"persist"})
     * @Expose()
     */
    protected $layers;

    /**
     * Map contexts (ie snapshot of map state)
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", mappedBy="map", cascade={"persist", "remove"})
     */
    protected $contexts;

    /**
     * @ORM\Column(name="cell_size", type="integer")
     * @Expose()
     * @var int
     */
    protected $cellSize;

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
        $jsonLayers = [];
        foreach ($this->getLayers() as $layer) {
            $jsonLayers[$layer->getId()] = $layer->toArray();
        }

        $jsonPencilSets = [];
        foreach ($this->getPencilSets() as $pencilSet) {
            $jsonPencilSets[] = $pencilSet->toArray();
        }

        $json = [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'layers' => $jsonLayers,
            'pencilSets' => $jsonPencilSets,
        ];
        return $json;
    }

    /**
     * @return PencilSet[]
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
     * @return ArrayCollection
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
     * @return ArrayCollection
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

    /**
     * @return int
     */
    public function getCellSize()
    {
        return $this->cellSize;
    }

    /**
     * @param int $cellSize
     */
    public function setCellSize($cellSize)
    {
        $this->cellSize = $cellSize;
    }
}
