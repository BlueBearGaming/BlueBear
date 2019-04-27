<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Descriptionable;
use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Timestampable;
use App\Entity\Behavior\Typeable;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * The map
 */
class Map
{
    use Id, Nameable, Label, Timestampable, Typeable, Descriptionable;

    /**
     * Map pencil sets
     */
    protected $pencilSets;

    /**
     * Map layers
     */
    protected $layers;

    /**
     * Map user contexts (ie snapshot of map state for user)
     */
    protected $contexts;

    /**
     * @var int
     */
    protected $cellSize;

    /**
     * Starting point x coordinates
     *
     * @var int
     */
    protected $startX = 0;

    /**
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
        $this->createdAt = new DateTime();
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
