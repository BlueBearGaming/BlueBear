<?php


namespace BlueBear\BaseBundle\Entity\Behaviors;

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
