<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

use JMS\Serializer\Annotation\Expose;

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
     * @ORM\Column(type="string", length=255)
     * @Expose()
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
        return $this->label;
    }
} 