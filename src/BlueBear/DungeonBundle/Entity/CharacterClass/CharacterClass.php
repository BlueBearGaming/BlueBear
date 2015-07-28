<?php

namespace BlueBear\DungeonBundle\Entity\CharacterClass;

use BlueBear\DungeonBundle\Annotation as Game;
use BlueBear\DungeonBundle\UnitOfWork\EntityReferenceCollection;

class CharacterClass
{
    /**
     * @Game\Id()
     */
    public $code;

    /**
     *
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeSetter", type="OneToMany")
     * @var EntityReferenceCollection
     */
    public $attributeSetters;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Skill", type="OneToMany")
     */
    public $skills;

    public $label;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Feat", type="OneToMany")
     */
    public $feats;

    public $description;

    public $allowedArmors;

    /**
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\CharacterClass\Attack", type="OneToMany")
     * @var EntityReferenceCollection
     */
    public $attacks;
}
