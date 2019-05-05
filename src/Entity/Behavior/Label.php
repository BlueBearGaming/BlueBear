<?php

namespace App\Entity\Behavior;

/**
 * Label
 *
 * Capacity to have a label (user friendly name)
 */
trait Label
{
    /**
     * Entity displayed name
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
