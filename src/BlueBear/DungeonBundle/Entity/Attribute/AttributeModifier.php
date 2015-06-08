<?php

namespace BlueBear\DungeonBundle\Entity\Attribute;

use BlueBear\DungeonBundle\Annotation as Game;

class AttributeModifier
{
    /**
     * @var string
     * @Game\Id()
     */
    protected $code;

    /**
     * @var Attribute
     * @Game\Relation(class="BlueBear\DungeonBundle\Entity\Attribute\Attribute", type="OneToOne")
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

    /**
     * @param Attribute $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @param int $modifier
     */
    public function setModifier($modifier)
    {
        $this->modifier = $modifier;
    }

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
}
