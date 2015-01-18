<?php


namespace BlueBear\CoreBundle\Entity\Behavior;

use JMS\Serializer\Annotation as Serializer;

/**
 * Nameable
 *
 * Capacity to have a type
 */
trait Typeable
{
    /**
     * Entity type
     *
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    protected $type;

    /**
     * Return entity type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set entity type
     *
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
} 