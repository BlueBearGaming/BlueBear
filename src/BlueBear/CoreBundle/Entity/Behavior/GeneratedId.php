<?php

namespace BlueBear\CoreBundle\Entity\Behavior;

trait GeneratedId
{
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        if (!$this->id) {
            $this->id = uniqid('bluebear_entity');
        }
        return $this->id;
    }
}