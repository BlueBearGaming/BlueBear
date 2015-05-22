<?php

namespace BlueBear\EngineBundle\Engine\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;

class EntityTypeAttribute
{
    use Nameable, Typeable, Label;
}