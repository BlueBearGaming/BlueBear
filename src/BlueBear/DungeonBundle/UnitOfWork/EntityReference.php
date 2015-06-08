<?php

namespace BlueBear\DungeonBundle\UnitOfWork;


class EntityReference
{
    protected $id;

    protected $class;

    protected $reference;

    public function __construct($class, $id = null)
    {
        $this->class = $class;
        $this->id = $id;
    }

    public function __call($method, $arguments = null)
    {
        if (!isset($this->reference)) {
            $this->reference = UnitOfWork::$instance->lazyLoad($this);
        }
        return $this->reference->$method($arguments);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}
