<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Descriptionable;
use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * A layer of the map, containing a collection of positioned mapItems
 *
 * @ORM\Table(name="layer")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\LayerRepository")
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom={"id", "name", "label", "description", "index"})
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
     * @Serializer\Expose()
     */
    protected $index;

    /**
     *
     *
     * @param Layer[] $layers
     * @return bool
     */
    public function isAllowed(array $layers)
    {
        foreach ($layers as $layer) {
            if ($layer->getId() == $this->getId() and
                $layer->getName() == $this->getName()
            ) {
                return true;
            }
        }
        return false;
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
}