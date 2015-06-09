<?php

namespace BlueBear\DungeonBundle\Entity;

use BlueBear\DungeonBundle\Annotation as Game;

class Skill
{
    /**
     * @Game\Id()
     */
    public $code;

    public $description;

    public $attribute;
}
