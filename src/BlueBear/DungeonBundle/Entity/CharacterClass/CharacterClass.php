<?php

namespace BlueBear\DungeonBundle\Entity\CharacterClass;

use BlueBear\DungeonBundle\Annotation as Game;

class CharacterClass
{
    /**
     * @Game\Id()
     */
    public $code;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeSetter", type="OneToMany")
     */
    public $attributeSetters;
}
