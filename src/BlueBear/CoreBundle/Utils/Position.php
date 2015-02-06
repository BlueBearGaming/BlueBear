<?php

namespace BlueBear\CoreBundle\Utils;

use BlueBear\CoreBundle\Entity\Behavior\Positionable;

class Position
{
    use Positionable;

    public function __construct($x = null, $y = null)
    {
        $this->x = $x;
        $this->y = $y;
    }
}