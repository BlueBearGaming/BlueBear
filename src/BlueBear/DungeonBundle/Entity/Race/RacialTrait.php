<?php

namespace BlueBear\DungeonBundle\Entity\Race;

use BlueBear\DungeonBundle\Annotation as Game;

class RacialTrait
{
    /**
     * @var string
     * @Game\Id()
     */
    public $code;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeSetter", type="OneToMany")
     */
    public $attributeSetters;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeModifier", type="OneToMany")
     */
    public $attributeModifiers;

    public $description;
}
