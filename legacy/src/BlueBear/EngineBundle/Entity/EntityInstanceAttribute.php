<?php

namespace BlueBear\EngineBundle\Entity;

use App\Entity\Behavior\Id;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Typeable;
use App\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="entity_instance_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\EngineBundle\Repository\EntityInstanceAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityInstanceAttribute
{
    use Id, Nameable, Valuable, Typeable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\EngineBundle\Entity\EntityInstance", inversedBy="attributes")
     * @ORM\JoinColumn(name="entity_instance_id", onDelete="CASCADE")
     */
    protected $entityInstance;

    /**
     * Define if current attribute inherits from configuration
     *
     * @var bool
     * @ORM\Column(name="is_default", type="boolean")
     */
    protected $isDefault = false;

    /**
     * Hydrate instance attribute from model default data
     *
     * @param EntityModelAttribute $entityModelAttribute
     */
    public function hydrateFromModel(EntityModelAttribute $entityModelAttribute)
    {
        $this->name = $entityModelAttribute->getName();
        $this->value = $entityModelAttribute->getValue();
        $this->type = $entityModelAttribute->getType();
    }

    /**
     * @return mixed
     */
    public function getEntityInstance()
    {
        return $this->entityInstance;
    }

    /**
     * @param mixed $entityInstance
     */
    public function setEntityInstance($entityInstance)
    {
        $this->entityInstance = $entityInstance;
    }

    /**
     * @return boolean
     */
    public function isIsDefault()
    {
        return $this->isDefault;
    }

    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param boolean $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }
}
