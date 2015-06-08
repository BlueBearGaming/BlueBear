<?php

namespace BlueBear\DungeonBundle\UnitOfWork;

use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UnitOfWork
{
    /**
     * @var UnitOfWork
     */
    public static $instance;

    protected $entities = [];

    protected $idProperties = [];

    protected $propertyAccessor;

    /**
     * Initialize property accessor and unit of work instance
     */
    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        UnitOfWork::$instance = $this;
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

    public function load(EntityReference $entityReference)
    {
        if (!$this->isClassRegistered($entityReference->getClass())) {
            throw new Exception("Class {$entityReference->getClass()} is not registered in unit of work");
        }
        if (!$entityReference->getId()) {
            throw new Exception('Entity reference has no id');
        }
        if (!array_key_exists($entityReference->getId(), $this->entities[$entityReference->getClass()])) {
            throw new Exception("Entity {$entityReference->getId()} of class {$entityReference->getClass()} was not found");
        }
        $entity = $this->entities[$entityReference->getClass()][$entityReference->getId()];

        return $entity;
    }

    public function getIdProperty($class)
    {
        if (!$this->isClassRegistered($class)) {
            throw new Exception("Class {$class} is not registered in unit of work");
        }
        return $this->idProperties[$class];
    }

    public static function lazyLoad(EntityReference $entityReference)
    {
        $unitOfWork = UnitOfWork::$instance;

        return $unitOfWork->load($entityReference);
    }
}
