<?php

namespace BlueBear\GameBundle\Game;

use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;

class EntityType
{
    use Nameable, Label;

    /**
     * @var EntityTypeAttribute[]
     */
    protected $attributes = [];

    protected $behaviors = [];

    /**
     * @return EntityTypeAttribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param EntityTypeAttribute[] $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param EntityTypeAttribute $unitTypeAttribute
     */
    public function addAttribute(EntityTypeAttribute $unitTypeAttribute)
    {
        $this->attributes[] = $unitTypeAttribute;
    }

    /**
     * @return array
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @param array $behaviors
     */
    public function setBehaviors($behaviors)
    {
        $this->behaviors = $behaviors;
    }

    public function addBehavior($behavior)
    {
        $this->behaviors[] = $behavior;
    }
}