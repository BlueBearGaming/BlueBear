<?php

namespace App\Entity\Behavior;

trait Id
{
    /**
     * Entity id
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
