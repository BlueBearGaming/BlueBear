<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="unit_instance_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\UnitInstanceAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UnitInstanceAttribute
{
    use Id, Nameable, Valuable;
}
