<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Data;
use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Timestampable;
use Doctrine\Common\Collections\Collection;

/**
 * Context of a map.
 */
class Context
{
    use Id, Label, Timestampable, Data;

    /**
     * @var Context[]|Collection
     */
    protected $contexts;

    /**
     * @var Map
     */
    protected $map;

    /**
     * Map item for this context
     *
     * @var MapItem[]|Collection
     */
    protected $mapItems;

    /**
     * @var int
     */
    protected $version = 0;

    protected $listeners = [];

    protected $room;

    /**
     * @return MapItem[]|Collection
     */
    public function getMapItems()
    {
        return $this->mapItems;
    }

    /**
     * @param mixed $mapItems
     */
    public function setMapItems($mapItems)
    {
        $this->mapItems = $mapItems;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setVersion()
    {
        $this->version = $this->version + 1;
    }

    /**
     * @return UserContext[]|Collection
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
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param mixed $listeners
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * @param string $layerName
     * @param int $x
     * @param int $y
     * @return MapItem[]
     */
    public function getMapItemsByLayerNameAndPosition($layerName, $x, $y)
    {
        $mapItems = [];
        foreach ($this->getMapItems() as $mapItem) {
            if ($mapItem->getLayerName() === $layerName && $mapItem->getX() === $x && $mapItem->getY() === $y) {
                $mapItems[] = $mapItem;
            }
        }
        return $mapItems;
    }

    /**
     * @param string $layerName
     * @param int $x
     * @param int $y
     * @return MapItem|null
     */
    public function getMapItemByLayerNameAndPosition($layerName, $x, $y)
    {
        foreach ($this->getMapItems() as $mapItem) {
            if ($mapItem->getLayerName() === $layerName && $mapItem->getX() === $x && $mapItem->getY() === $y) {
                return $mapItem;
            }
        }
        return null;
    }
}
