<?php

namespace BlueBear\EngineBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use JMS\Serializer\Annotation as Serializer;

/**
 * EntityInstance
 *
 * Represents a instance of an entity model on map
 *
 * @ORM\Table(name="entity_instance")
 * @ORM\Entity(repositoryClass="BlueBear\EngineBundle\Repository\EntityInstanceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom={"id", "name", "label", "attributes"})
 */
class EntityInstance
{
    use Id, Nameable, Label, Timestampable, Typeable;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\EngineBundle\Entity\EntityInstanceAttribute", cascade={"persist", "remove"}, mappedBy="entityInstance", indexBy="name")
     * @Serializer\Expose()
     * @Serializer\Type("array<BlueBear\EngineBundle\Entity\EntityInstanceAttribute>")
     */
    protected $attributes;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\MapItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="map_item_id", onDelete="CASCADE")
     */
    protected $mapItem;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil")
     */
    protected $pencil;

    /**
     * @ORM\Column(name="behaviors", type="array")
     */
    protected $behaviors;

    /**
     * Allowed layers for this entity
     *
     * @ORM\Column(name="allowed_layer_types", type="simple_array", nullable=true)
     */
    protected $allowedLayerTypes;

    /**
     * Initialize collection
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @Serializer\PostDeserialize()
     */
    public function postDeserialize()
    {
        if (is_array($this->attributes)) {
            $attributes = $this->attributes;
            $this->attributes = new ArrayCollection();
            /** @var EntityInstanceAttribute $attribute */
            foreach ($attributes as $attribute) {
                $this->attributes->set($attribute->getName(), $attribute);
            }
        }
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
        $this->pencil = $entityModel->getPencil();

        /** @var EntityModelAttribute $entityModelAttribute */
        foreach ($entityModel->getAttributes() as $entityModelAttribute) {
            $instanceAttribute = new EntityInstanceAttribute();
            $instanceAttribute->hydrateFromModel($entityModelAttribute);
            $instanceAttribute->setEntityInstance($this);
            $this->attributes->add($instanceAttribute);
        }
        foreach ($entityModel->getBehaviors() as $entityModelBehavior) {
            $this->behaviors[] = $entityModelBehavior;
        }
    }

    /**
     * @return MapItem
     */
    public function getMapItem()
    {
        return $this->mapItem;
    }

    /**
     * @param MapItem $mapItem
     */
    public function setMapItem(MapItem $mapItem)
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
        /** @var EntityInstanceAttribute $attribute */
        foreach ($attributes as $attribute) {
            $this->attributes->set($attribute->getName(), $attribute);
            $attribute->setEntityInstance($this);
        }
    }

    public function addAttribute(EntityInstanceAttribute $attribute)
    {
        $this->attributes->add($attribute);
        $attribute->setEntityInstance($this);
    }

    public function has($attributeName)
    {
        return array_key_exists($attributeName, $this->attributes->toArray());
    }

    /**
     * Return the value of an attribute
     *
     * @param $attributeName
     * @return mixed
     * @throws Exception
     */
    public function get($attributeName)
    {
        if (!array_key_exists($attributeName, $this->attributes->toArray())) {
            throw new Exception('Attribute not found : ' . $attributeName);
        }
        return $this->attributes[$attributeName]->getValue();
    }

    /**
     * Set the value of an attribute
     *
     * @param $attributeName
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function set($attributeName, $value)
    {
        if (!array_key_exists($attributeName, $this->attributes->toArray())) {
            throw new Exception('Attribute not found : ' . $attributeName);
        }
        return $this->attributes[$attributeName]->setValue($value);
    }

    /**
     * @return mixed
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @param $behaviorName
     * @return bool
     */
    public function hasBehavior($behaviorName)
    {
        return in_array($behaviorName, $this->behaviors);
    }

    /**
     * @param mixed $behaviors
     */
    public function setBehaviors($behaviors)
    {
        $this->behaviors = $behaviors;
    }

    /**
     * @return mixed
     */
    public function getPencil()
    {
        return $this->pencil;
    }

    /**
     * @param mixed $pencil
     */
    public function setPencil($pencil)
    {
        $this->pencil = $pencil;
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
