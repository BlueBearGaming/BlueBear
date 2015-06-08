<?php

namespace BlueBear\RogueBearBundle\Engine\Entity;


class Feat
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var AttributeModifier[]
     */
    protected $attributeModifiers;

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
     * @return AttributeModifier[]
     */
    public function getAttributeModifiers()
    {
        return $this->attributeModifiers;
    }

    /**
     * @param AttributeModifier[] $attributeModifiers
     */
    public function setAttributeModifiers($attributeModifiers)
    {
        $this->attributeModifiers = $attributeModifiers;
    }
}
