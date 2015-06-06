<?php

namespace BlueBear\EngineBundle\Engine\UnitOfWork;

use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UnitOfWork
{
    protected $entities = [];

    protected $idProperties = [];

    protected $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function add($entity)
    {
        $class = get_class($entity);

        if (!$this->isClassRegistered($class)) {
            throw new Exception('Class ' . $class . 'is should registered before adding it to the unit of work');
        }
        $idProperty = $this->idProperties[$class];
        $idValue = $this->propertyAccessor->getValue($entity, $idProperty);

        if (!$idValue) {
            throw new Exception("Cannot add entity {$class} with an empty id");
        }
        $this->entities[$class][$idValue] = $entity;
    }

    public function registerClass($class, $idProperty)
    {
        if ($this->isClassRegistered($class)) {
            throw new Exception('Class ' . $class . ' is already registered in unit of work');
        }
        $this->idProperties[$class] = $idProperty;
        $this->entities[$class] = [];
    }

    public function isClassRegistered($class)
    {
        return array_key_exists($class, $this->entities) and array_key_exists($class, $this->idProperties);
    }
}
