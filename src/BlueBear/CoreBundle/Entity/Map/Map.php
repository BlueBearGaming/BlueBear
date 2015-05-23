<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * Starting point x coordinates
     *
     * @ORM\Column(name="start_x", type="integer")
     * @Serializer\Expose()
     * @var int
     */
    protected $startX = 0;

    /**
     * @ORM\Column(name="start_y", type="integer")
     * @Serializer\Expose()
     * @var int
     */
    protected $startY = 0;

    /**
     * Initialize user context
     */
    public function __construct()
    {
        $this->contexts = new ArrayCollection();
        $this->pencilSets = new ArrayCollection();
    }

    /**
     * @return PencilSet[]|Collection
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

    public function addPencilSet(PencilSet $pencilSet)
    {
        $this->pencilSets->add($pencilSet);
    }

    /**
     * @return Layer[]|Collection
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
     * @return Context[]|Collection
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
     * @return int
     */
    public function getStartX()
    {
        return $this->startX;
    }

    /**
     * @param int $startX
     */
    public function setStartX($startX)
    {
        $this->startX = $startX;
    }

    /**
     * @return int
     */
    public function getStartY()
    {
        return $this->startY;
    }

    /**
     * @param int $startY
     */
    public function setStartY($startY)
    {
        $this->startY = $startY;
    }

    /**
     * @param string $pencilName
     * @return Pencil|null
     */
    public function getPencilByName($pencilName)
    {
        foreach ($this->getPencilSets() as $pencilSet) {
            $pencil = $pencilSet->getPencilByName($pencilName);
            if ($pencil) {
                return $pencil;
            }
        }
        return null;
    }

    /**
     * @param string $layerName
     * @return Layer|null
     */
    public function getLayerByName($layerName)
    {
        foreach ($this->getLayers() as $layer) {
            if ($layer->getName() === $layerName) {
                return $layer;
            }
        }
        return null;
    }
}
