<?php

namespace BlueBear\GameBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UnitInstance
 *
 * Represents a instance of a unit on map
 *
 * @ORM\Table(name="entity_instance")
 * @ORM\Entity(repositoryClass="BlueBear\GameBundle\Entity\EntityInstanceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityInstance
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="BlueBear\GameBundle\Entity\EntityInstanceAttribute", cascade={"persist"})
     */
    protected $attributes;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem", mappedBy="entityInstance")
     */
    protected $mapItem;

    /**
     * @ORM\Column(name="behaviors", type="array")
     */
    protected $behaviors = [];

    /**
     *
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * Hydrate entity instance from model default data
     *
     * @param EntityModel $entityModel
     */
    public function hydrateFromModel(EntityModel $entityModel)
    {
        $this->name = $entityModel->getName();
        $this->label = $entityModel->getLabel();
        $this->type = $entityModel->getType();
        /** @var EntityModelAttribute $entityModelAttribute */
        foreach ($entityModel->getAttributes() as $entityModelAttribute) {
            $instanceAttribute = new EntityInstanceAttribute();
            $instanceAttribute->hydrateFromModel($entityModelAttribute);
            $this->attributes->add($instanceAttribute);
        }
    }

    /**
     * @return mixed
     */
    public function getMapItem()
    {
        return $this->mapItem;
    }

    /**
     * @param mixed $mapItem
     */
    public function setMapItem($mapItem)
    {
        $this->mapItem = $mapItem;
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

    /**
     * @return mixed
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @param mixed $behaviors
     */
    public function setBehaviors($behaviors)
    {
        $this->behaviors = $behaviors;
    }
}