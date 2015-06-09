<?php

namespace BlueBear\DungeonBundle\Entity\Attribute;

use BlueBear\DungeonBundle\Annotation as Game;

class AttributeSetter
{
    /**
     * @var string
     * @Game\Id()
     */
    public $code;

    /**
     * @var Attribute
     */
    public $attribute;

    /**
     * @var int
     */
    public $setter;
}
