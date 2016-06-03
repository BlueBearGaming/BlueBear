<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Data;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Context of a map
 *
 * @ORM\Table(name="context")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\ContextRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom={"id", "label", "map", "mapItems", "listeners"})
 */
class Context
{
    use Id, Nameable, Label, Timestampable, Data;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Game\Player\Player", inversedBy="contexts")
     */
    protected $player;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Map", fetch="EAGER", inversedBy="contexts")
     * @Serializer\Expose()
     */
    protected $map;

    /**
     * Map item for this context
     *
     * @ORM\OneToMany(
     *     targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem",
     *     mappedBy="context",
     *     cascade={"persist", "remove"},
     *     indexBy="id"
     * )
     * @Serializer\Expose()
     */
    protected $mapItems;

    /**
     * @ORM\Column(type="integer")
     */
    protected $version = 0;

    /**
     * @Serializer\Expose()
     */
    protected $listeners = [];

    protected $room;

    public function __construct()
    {
        $this->mapItems = new ArrayCollection();
    }

    /**
     * Return the map items linked to this context, sorted by id.
     *
     * @return MapItem[]|Collection
     */
    public function getMapItems()
    {
        return $this->mapItems;
    }

    /**
     * Define the map items linked to this context and indexed them by id.
     *
     * @param MapItem[]|Collection $mapItems
     */
    public function setMapItems($mapItems)
    {
        $this->mapItems = new ArrayCollection();

        foreach ($mapItems as $mapItem) {
            $this->mapItems->set($mapItem->getId(), $mapItem);
        }
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
    public function getUserContexts()
    {
        return $this->userContexts;
    }

    /**
     * @param mixed $userContexts
     */
    public function setUserContexts($userContexts)
    {
        $this->userContexts = $userContexts;
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

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }
}
