<?php

namespace BlueBear\GameBundle\Game;

class EntityBehavior 
{
    protected $name;

    protected $listener;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * @param mixed $listener
     */
    public function setListener($listener)
    {
        $this->listener = $listener;
    }
}