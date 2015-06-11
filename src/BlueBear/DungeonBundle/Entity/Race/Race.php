<?php

namespace BlueBear\DungeonBundle\Entity\Race;

use BlueBear\DungeonBundle\Annotation as Game;
use BlueBear\DungeonBundle\UnitOfWork\EntityReferenceCollection;

class Race
{
    /**
     * @Game\Id()
     */
    public $code;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeModifier", type="OneToMany")
     */
    public $attributeModifiers;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\ClassSize", type="OneToOne")
     */
    public $classSize;

    /**
     * @var
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Race\RacialTrait", type="OneToMany")
     */
    public $racialTraits;

    public $label;

    public $languages;

    public $weaponFamiliarities;

    public $description;

    public $longDescription;
}
