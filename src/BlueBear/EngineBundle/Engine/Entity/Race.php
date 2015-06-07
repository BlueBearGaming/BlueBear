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
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\AttributeModifier", type="OneToMany")
     */
    protected $attributeModifiers;

    /**
     * @var EntityReferenceCollection
     * @Game\Relation(class="BlueBear\EngineBundle\Engine\Entity\ClassSize", type="OneToOne")
     */
    protected $classSize;

    /**
     * @var
     */
    protected $racialTraits;

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
}
