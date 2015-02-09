<?php

namespace BlueBear\GameBundle\Game;

use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;

class EntityTypeAttribute
{
    use Nameable, Typeable, Label;
}