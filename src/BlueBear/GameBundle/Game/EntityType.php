<?php

namespace BlueBear\GameBundle\Game;

use BlueBear\CoreBundle\Entity\Behavior\Nameable;

class EntityType
{
    use Nameable;

    /**
     * @var EntityTypeAttribute[]
     */
    protected $attributes = [];

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
}