<?php

namespace BlueBear\CoreBundle\Entity\Map;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Units attributes
 *
 * @ORM\Table(name="attribute")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Map\AttributeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Attribute
{
    use Id, Nameable, Label, Timestampable, Typeable;
}