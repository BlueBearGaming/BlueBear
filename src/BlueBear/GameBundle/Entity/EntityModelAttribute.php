<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="entity_model_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\EntityModelAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityModelAttribute
{
    use Id, Nameable, Valuable;
}
