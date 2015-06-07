<?php

namespace BlueBear\EngineBundle\Engine\Entity;

use BlueBear\EngineBundle\Engine\Annotation as Game;
use BlueBear\EngineBundle\Engine\UnitOfWork\EntityReferenceCollection;
use Doctrine\Common\Collections\Collection;

class Race
{
    /**
     * @var string
     * @Game\Id()
     */
    protected $code;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\AttributeModifier")
     */
    protected $attributeModifiers;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\ClassSize")
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
     * @return string
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
     * @return Feat[]
     */
    public function getFeats()
    {
        return $this->feats;
    }

    /**
     * @param Feat[] $feats
     */
    public function setFeats($feats)
    {
        $this->feats = $feats;
    }

    /**
     * @return WeaponProficiency[]
     */
    public function getWeaponFamiliarities()
    {
        return $this->weaponFamiliarities;
    }

    /**
     * @param WeaponProficiency[] $weaponFamiliarities
     */
    public function setWeaponFamiliarities($weaponFamiliarities)
    {
        $this->weaponFamiliarities = $weaponFamiliarities;
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
}
