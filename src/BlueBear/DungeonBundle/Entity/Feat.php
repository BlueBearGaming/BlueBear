<?php

namespace BlueBear\DungeonBundle\Entity;

use BlueBear\DungeonBundle\Annotation as Game;

class Feat
{
    /**
     * @Game\Id()
     */
    public $code;
    public $label;
    public $type;
    public $condition;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeModifier", type="OneToMany")
     */
    public $attributeModifiers;
}
