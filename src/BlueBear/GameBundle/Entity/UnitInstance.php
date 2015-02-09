<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * UnitInstance
 *
 * Represents a instance of a unit on map
 *
 * @ORM\Table(name="unit_instance")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\UnitInstanceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UnitInstance
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="BlueBear\GameBundle\Entity\UnitInstanceAttribute", cascade={"persist"})
     */
    protected $attributes;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem")
     */
    protected $mapItem;

    public function hydrateFromModel(UnitModel $unit)
    {
        $this->id = $unit->getId();
        $this->name = $unit->getName();
        $this->label = $unit->getLabel();
        $this->type = $unit->getType();
        $this->attributes = $unit->getAttributes();
    }

    /**
     * @return mixed
     */
    public function getMapItem()
    {
        return $this->mapItem;
    }

    /**
     * @param mixed $mapItem
     */
    public function setMapItem($mapItem)
    {
        $this->mapItem = $mapItem;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}