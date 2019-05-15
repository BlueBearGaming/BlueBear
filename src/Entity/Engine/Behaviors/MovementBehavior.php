<?php

namespace App\Entity\Engine\Behaviors;

use App\Entity\Behavior\Timestampable;
use App\Entity\Engine\GameBehavior;

class MovementBehavior extends GameBehavior
{
    use Timestampable;

    protected $x = 0;
    protected $y = 0;
    protected $z = 0;
    protected $movement = 0;
    protected $movementMax = 0;
}
