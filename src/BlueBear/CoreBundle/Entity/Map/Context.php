<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Data;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessorOrder;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Context of a map
 *
 * @ORM\Table(name="context")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\ContextRepository")
 * @ORM\HasLifecycleCallbacks()
 * @AccessorOrder("custom", custom={"id", "label", "map", "mapItems"})
 * @ExclusionPolicy("all")
 */
class Context
{
    use Id, Label, Timestampable, Data;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\UserContext", mappedBy="context")
     */
    protected $userContexts;

    /**
     * Map item for this context
     *
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem", mappedBy="context", cascade={"persist"})
     * @Expose()
     */
    protected $mapItems;

    /**
     * @ORM\Column(type="integer")
     */
    protected $version = 0;

    /**
     * Convert the context to an array
     *
     * @return array
     */
    public function toArray()
    {
        // TODO remove this method
        $mapItem = $this->getMapItems();
        $mapItemsArray = [];

        /** @var MapItem $item */
        foreach ($mapItem as $item) {
            $mapItemsArray[] = $item->toArray();
        }
        return [
            'id' => $this->getId(),
            'mapItems' => $mapItemsArray
        ];
    }

    /**
     * @return mixed
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

    public function getMapItemsById()
    {
        $mapItems = [];

        /** @var MapItem $item */
        foreach ($this->mapItems as $item) {
            $mapItems[$item->getId()] = $item;
        }
        return $mapItems;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
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
} 