<?php

namespace App\Entity\Behavior;

trait Descriptionable
{
    /**
     * Entity description
     */
    protected $description;

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
} 
