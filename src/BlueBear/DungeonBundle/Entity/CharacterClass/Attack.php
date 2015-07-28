<?php

namespace BlueBear\DungeonBundle\Entity\CharacterClass;

use BlueBear\DungeonBundle\Annotation as Game;

class Attack
{
    /**
     * @Game\Id()
     */
    public $code;

    public $label;

    public $description;

    public $damage;
}
