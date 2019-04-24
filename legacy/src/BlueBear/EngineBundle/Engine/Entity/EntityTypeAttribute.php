<?php

namespace BlueBear\EngineBundle\Engine\Entity;

use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Typeable;

class EntityTypeAttribute
{
    use Nameable, Typeable, Label;
}
