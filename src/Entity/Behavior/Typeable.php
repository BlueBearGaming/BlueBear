<?php


namespace App\Entity\Behavior;

/**
 * Nameable
 *
 * Capacity to have a type
 */
trait Typeable
{
    /**
     * Entity type
     */
    protected $type;

    /**
     * Return entity type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set entity type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
} 
