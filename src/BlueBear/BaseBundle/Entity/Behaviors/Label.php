<?php

namespace BlueBear\BaseBundle\Entity\Behaviors;


trait Label
{
    /**
     * Entity label
     *
     * @ORM\Column(name="label", type="string")
     */
    protected $label;

    /**
     * Return entity label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set entity label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
}
