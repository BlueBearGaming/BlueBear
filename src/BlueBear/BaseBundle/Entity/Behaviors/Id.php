<?php

namespace BlueBear\BaseBundle\Entity\Behaviors;

trait Id
{
    /**
     * Entity id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Return entity id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entity id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
