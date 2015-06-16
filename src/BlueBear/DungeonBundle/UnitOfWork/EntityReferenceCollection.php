<?php

namespace BlueBear\DungeonBundle\UnitOfWork;

use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityReferenceCollection
{
    protected $entityClass;

    protected $entityProperty;

    protected $propertyAccessor;

    protected $entityReferences = [];

    public function __construct($entityClass, $entityProperty)
    {
        $this->entityClass = $entityClass;
        $this->entityProperty = $entityProperty;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function add($element)
    {
        if (get_class($element) == $this->entityClass) {
            $this->entityReferences[$this->propertyAccessor->getValue($this->entityClass, $this->entityProperty)] = $element;
        } else if ($element instanceof EntityReference) {
            $this->entityReferences[$element->getId()] = $element;
        } else {
            throw new Exception('Invalid class ' . get_class($element). ' for adding in collection of ' . $this->entityClass);
        }
    }

    /**
     * Clears the collection, removing all elements
     */
    public function clear()
    {
        $this->entityReferences = [];
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element)
    {
        return in_array($element, $this->entityReferences);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return count($this->entityReferences) == 0;
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $key The key/index to check for.
     *
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *                 FALSE otherwise.
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->entityReferences);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return mixed
     */
    public function get($key)
    {
        $entityReference = $this->entityReferences[$key];

        if ($entityReference instanceof EntityReference) {
            $this->entityReferences[$key] = UnitOfWork::lazyLoad($entityReference);
        }
        return $this->entityReferences[$key];
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    public function getKeys()
    {
        return array_keys($this->entityReferences);
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    public function getValues()
    {
        return $this->entityReferences;
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->entityReferences;
    }

    public function count()
    {
        return count($this->entityReferences);
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
