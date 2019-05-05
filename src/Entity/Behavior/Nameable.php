<?php

namespace App\Entity\Behavior;

/**
 * Nameable
 *
 * Capacity to have a name
 */
trait Nameable
{
    /**
     * Entity name
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
