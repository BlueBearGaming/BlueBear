<?php

namespace BlueBear\EngineBundle\Engine\Entity;


class AttributeModifier
{
    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * @var int
     */
    protected $modifier;

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return int
     */
    public function getModifier()
    {
        return $this->modifier;
    }
}
