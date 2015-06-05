<?php

namespace BlueBear\EngineBundle\Engine\Entity;


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

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return AttributeModifier[]
     */
    public function getAttributeModifiers()
    {
        return $this->attributeModifiers;
    }

    /**
     * @return ClassSize
     */
    public function getClassSize()
    {
        return $this->classSize;
    }

    /**
     * @return Feat[]
     */
    public function getFeats()
    {
        return $this->feats;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param AttributeModifier[] $attributeModifiers
     */
    public function setAttributeModifiers($attributeModifiers)
    {
        $this->attributeModifiers = $attributeModifiers;
    }

    /**
     * @param string $classSize
     */
    public function setClassSize($classSize)
    {
        $this->classSize = $classSize;
    }

    /**
     * @param Feat[] $feats
     */
    public function setFeats($feats)
    {
        $this->feats = $feats;
    }

    /**
     * @param WeaponProficiency[] $weaponFamiliarities
     */
    public function setWeaponFamiliarities($weaponFamiliarities)
    {
        $this->weaponFamiliarities = $weaponFamiliarities;
    }
    /**
     * @return WeaponProficiency[]
     */
    public function getWeaponFamiliarities()
    {
        return $this->weaponFamiliarities;
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
