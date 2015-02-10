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
 * @ORM\Table(name="entity_model")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\EntityModelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityModel
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="BlueBear\GameBundle\Entity\EntityModelAttribute", cascade={"persist"}, fetch="EAGER")
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
