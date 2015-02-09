<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="unit_model_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\UnitModelAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UnitModelAttribute
{
    use Id, Nameable, Valuable;
}
