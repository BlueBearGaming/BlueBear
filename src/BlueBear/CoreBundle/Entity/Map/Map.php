<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * The map
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\MapRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom={"id", "name", "label", "type", "cellSize", "layers", "pencilSets"})
 */
class Map
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * Map pencil sets
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\PencilSet", cascade={"persist"})
     * @Serializer\Expose()
     */
    protected $pencilSets;

    /**
     * Map layers
     *
     * @ORM\ManyToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Layer", cascade={"persist"})
     * @Serializer\Expose()
     */
    protected $layers;

    /**
     * Map user contexts (ie snapshot of map state for user)
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\Context", mappedBy="map", cascade={"persist", "remove"})
     */
    protected $contexts;

    /**
     * @ORM\Column(name="cell_size", type="integer")
     * @Serializer\Expose()
     * @var int
     */
    protected $cellSize;

    /**
     * Initialize user context
     */
    public function __construct()
    {
        $this->contexts = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
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
}
