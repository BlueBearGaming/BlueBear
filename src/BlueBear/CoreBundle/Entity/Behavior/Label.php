<?php


namespace BlueBear\CoreBundle\Entity\Behavior;


trait Label
{
    /**
     * Entity displayed name
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $label;

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
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