<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * A unit
 *
 * @ORM\Table(name="entity_model")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\EntityModelRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name")
 */
class EntityModel
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BlueBear\GameBundle\Entity\EntityModelAttribute", mappedBy="entityModel", cascade={"persist", "remove"}, fetch="EAGER")
     */
    protected $attributes;

    /**
     * Allowed layers for this entity
     *
     * @ORM\Column(name="allowed_layer_types", type="simple_array", nullable=true)
     */
    protected $allowedLayerTypes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function addAttributes(EntityModelAttribute $entityModelAttribute)
    {
        $this->attributes->add($entityModelAttribute);
        $entityModelAttribute->setEntityModel($this);
    }

    /**
     * @return mixed
     */
    public function getAllowedLayerTypes()
    {
        return $this->allowedLayerTypes;
    }

    /**
     * @param mixed $allowedLayerTypes
     */
    public function setAllowedLayerTypes($allowedLayerTypes)
    {
        $this->allowedLayerTypes = $allowedLayerTypes;
    }
}
