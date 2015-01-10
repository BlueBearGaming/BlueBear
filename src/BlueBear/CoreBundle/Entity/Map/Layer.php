<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Descriptionable;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessorOrder;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * A layer of the map, containing a collection of positioned mapItems
 *
 * @ORM\Table(name="layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\LayerRepository")
 * @ExclusionPolicy("all")
 * @AccessorOrder("custom", custom={"id", "name", "label", "description", "index"})
 */
class Layer
{
    use Id, Nameable, Label, Typeable, Descriptionable;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem", mappedBy="layer")
     */
    protected $mapItems;

    /**
     * Layer index (works similarly to css z-index; higher index means on the top of the screen layers)
     *
     * @ORM\Column(name="z_index", type="integer")
     * @Expose()
     */
    protected $index;

    // TODO remove this method after api test controller refactoring
    public function toArray()
    {
        $mapItemsJson = [];
        foreach ($this->mapItems as $mapItem) {
            $mapItemsJson[] = $mapItem->toArray();
        }
        $json = [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'mapItems' => $mapItemsJson,
        ];
        return $json;
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

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }
}