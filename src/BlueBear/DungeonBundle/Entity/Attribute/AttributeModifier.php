<?php

namespace BlueBear\DungeonBundle\Entity\Attribute;

use BlueBear\DungeonBundle\Annotation as Game;

class AttributeModifier
{
    /**
     * @var string
     * @Game\Id()
     */
    public $code;

    /**
     * @var Attribute
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\Attribute", type="OneToOne")
     */
    public $attribute;

    /**
     * @var int
     */
    public $modifier;

    public $duration;
}
