<?php

namespace BlueBear\EngineBundle\Entity;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Typeable;
use BlueBear\CoreBundle\Entity\Behavior\Valuable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic attributes
 *
 * @ORM\Table(name="entity_model_attribute")
 * @ORM\Entity(repositoryClass="BlueBear\EngineBundle\Repository\EntityModelAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EntityModelAttribute
{
    use Id, Nameable, Label, Valuable, Typeable;

    /**
     * @ORM\ManyToOne(targetEntity="BlueBear\GameBundle\Entity\EntityModel", inversedBy="attributes")
     * @ORM\JoinColumn(name="entity_model_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $entityModel;

    /**
     * Define if current attribute inherits from configuration
     *
     * @var bool
     * @ORM\Column(name="is_default", type="boolean")
     */
    protected $isDefault = false;

    /**
     * @return boolean
     */
    public function getIsDefault()
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

    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @return mixed
     */
    public function getEntityModel()
    {
        return $this->entityModel;
    }

    /**
     * @param mixed $entityModel
     */
    public function setEntityModel($entityModel)
    {
        $this->entityModel = $entityModel;
    }
}
