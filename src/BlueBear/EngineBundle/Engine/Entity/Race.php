<?php

namespace BlueBear\EngineBundle\Engine\Entity;

use BlueBear\EngineBundle\Engine\Annotation as Game;

class Race
{
    /**
     * @var string
     * @Game\Id()
     */
    protected $code;

    /**
     * @var AttributeModifier[]
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\AttributeModifier")
     */
    protected $attributeModifiers;

    /**
     * @var string
     */
    protected $classSize;

    /**
     * @var Feat[]
     */
    protected $feats;

    /**
     * @var WeaponProficiency[]
     */
    protected $weaponFamiliarities;

    protected $languages;

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
