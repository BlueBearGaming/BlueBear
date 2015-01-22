<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * A unit
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\UnitRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Unit
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="BlueBear\GameBundle\Entity\Attribute", cascade={"persist"})
     */
    protected $attributes;

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
