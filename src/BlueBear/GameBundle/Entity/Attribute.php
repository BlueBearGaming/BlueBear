<?php

namespace BlueBear\GameBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="attributes")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\GameRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Attribute
{
    use Id, Nameable, Valuable;
}
