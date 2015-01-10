<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

use JMS\Serializer\Annotation\Expose;

trait Descriptionable
{
    /**
     * Entity description
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Expose()
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