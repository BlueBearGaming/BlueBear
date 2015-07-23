<?php

namespace BlueBear\DungeonBundle\Entity\Attribute;

use BlueBear\DungeonBundle\Annotation as Game;

class Attribute
{
    /**
     * @var string
     * @Game\Id()
     */
    public $code;

    public $creation = false;
}
