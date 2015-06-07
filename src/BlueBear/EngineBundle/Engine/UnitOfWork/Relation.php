<?php

namespace BlueBear\EngineBundle\Engine\UnitOfWork;


class Relation
{
    const RELATION_ONE_TO_MANY = 'OneToMany';
    const RELATION_ONE_TO_ONE = 'OneToOne';

    protected $attributeName;
    protected $relationClass;
    protected $owner;
    protected $data;
    protected $relationType;

    public function __construct($attributeName, $relationClass, $relationType, $owner, $data = null)
    {
        $this->attributeName = $attributeName;
        $this->relationClass = $relationClass;
        $this->owner = $owner;
        $this->data = $data;
        $this->relationType = $relationType;
    }

    /**
     * @return mixed
     */
    public function getAttributeName()
    {
        return $this->attributeName;
    }

    /**
     * @return mixed
     */
    public function getRelationClass()
    {
        return $this->relationClass;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return mixed
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * @param mixed $relationType
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;
    }
}
