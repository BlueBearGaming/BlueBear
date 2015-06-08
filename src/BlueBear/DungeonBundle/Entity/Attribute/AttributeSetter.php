<?php

namespace BlueBear\DungeonBundle\Entity\Attribute;

use BlueBear\DungeonBundle\Annotation as Game;

class AttributeSetter
{
    /**
     * @var string
     * @Game\Id()
     */
    protected $code;

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * @var int
     */
    protected $modifier;

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
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return int
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     * @param int $modifier
     */
    public function setModifier($modifier)
    {
        $this->modifier = $modifier;
    }

}
