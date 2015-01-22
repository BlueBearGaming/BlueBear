<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="attributes")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\AttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Attribute
{
    use Id, Nameable, Valuable;
}
