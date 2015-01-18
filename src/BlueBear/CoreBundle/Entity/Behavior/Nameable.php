<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

use JMS\Serializer\Annotation as Serializer;

/**
 * Nameable
 *
 * Capacity to have a name
 */
trait Nameable
{
    /**
     * Entity name
     *
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    protected $name;

    /**
     * Return entity name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entity name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
} 