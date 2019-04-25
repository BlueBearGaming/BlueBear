<?php

namespace App\Entity\Behavior;

use JMS\Serializer\Annotation as Serializer;

/**
 * Label
 *
 * Capacity to have a label (user friendly name)
 */
trait Label
{
    /**
     * Entity displayed name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     */
    protected $label;

    /**
     * Return entity label
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set entity label
     *
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Return entity label
     *
     * @return mixed
     */
    public function __toString()
    {
        return (string) $this->label;
    }
} 
