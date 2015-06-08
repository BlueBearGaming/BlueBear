<?php

namespace BlueBear\DungeonBundle\Entity\Race;

use BlueBear\DungeonBundle\Annotation as Game;
use BlueBear\DungeonBundle\Entity\ClassSize;
use BlueBear\DungeonBundle\UnitOfWork\EntityReferenceCollection;
use Doctrine\Common\Collections\Collection;

class Race
{
    /**
     * @Game\Id()
     */
    protected $code;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\AttributeModifier", type="OneToMany")
     */
    protected $attributeModifiers;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\ClassSize", type="OneToOne")
     */
    protected $classSize;

    /**
     * @var
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Race\RacialTrait", type="OneToMany")
     */
    protected $racialTraits;

    protected $languages;

    protected $weaponFamiliarities;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Collection
     */
    public function getAttributeModifiers()
    {
        return $this->attributeModifiers;
    }

    /**
     * @param Collection $attributeModifiers
     */
    public function setAttributeModifiers($attributeModifiers)
    {
        $this->attributeModifiers = $attributeModifiers;
    }

    /**
     * @return ClassSize
     */
    public function getClassSize()
    {
        return $this->classSize;
    }

    /**
     * @param string $classSize
     */
    public function setClassSize($classSize)
    {
        $this->classSize = $classSize;
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return mixed
     */
    public function getRacialTraits()
    {
        return $this->racialTraits;
    }

    /**
     * @param mixed $racialTraits
     */
    public function setRacialTraits($racialTraits)
    {
        $this->racialTraits = $racialTraits;
    }

    /**
     * @return mixed
     */
    public function getWeaponFamiliarities()
    {
        return $this->weaponFamiliarities;
    }

    /**
     * @param mixed $weaponFamiliarities
     */
    public function setWeaponFamiliarities($weaponFamiliarities)
    {
        $this->weaponFamiliarities = $weaponFamiliarities;
    }
}
