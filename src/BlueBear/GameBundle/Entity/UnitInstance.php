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
 * @ORM\Table(name="unit_instance")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class UnitInstance
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="BlueBear\GameBundle\Entity\Attribute", cascade={"persist"})
     */
    protected $attributes;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\MapItem", inversedBy="unit")
     */
    protected $mapItem;

    public function hydrateFromUnit(Unit $unit)
    {
        $this->id = $unit->getId();
        $this->name = $unit->getName();
        $this->label = $unit->getLabel();
        $this->type = $unit->getType();
        $this->attributes = $unit->getAttributes();
    }
}