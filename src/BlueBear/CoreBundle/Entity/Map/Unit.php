<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Sizable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * A unit
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\UnitRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Unit
{
    use Id, Nameable, Label, Timestampable, Typeable;
}
