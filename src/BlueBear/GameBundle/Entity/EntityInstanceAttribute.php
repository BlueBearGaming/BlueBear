<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="entity_instance_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\EntityInstanceAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityInstanceAttribute
{
    use Id, Nameable, Valuable, Typeable;

    /**
     * Hydrate instance attribute from model default data
     *
     * @param EntityModelAttribute $entityModelAttribute
     */
    public function hydrateFromModel(EntityModelAttribute $entityModelAttribute)
    {
        $this->name = $entityModelAttribute->getName();
        $this->value = $entityModelAttribute->getValue();
    }

    public function isDefault()
    {
        return true;
    }
}
