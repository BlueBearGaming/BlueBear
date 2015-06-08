<?php

namespace BlueBear\DungeonBundle\Annotation;

use Exception;
use BlueBear\DungeonBundle\UnitOfWork\Relation as Reference;

/**
 * @Annotation
 */
class Relation
{
    public $relationClass;

    public $relationType;

    public function __construct(array $data)
    {
        if (!array_key_exists('class', $data)) {
            throw new Exception('Annotation Relation should have a class attribute');
        }
        if (!array_key_exists('type', $data)) {
            throw new Exception('Annotation Relation should have a type attribute');
        }
        if (!in_array($data['type'], [Reference::RELATION_ONE_TO_ONE, Reference::RELATION_ONE_TO_MANY])) {
            throw new Exception('Only OneToMany and OneToOne relation are allowed');
        }
        $this->relationClass = $data['class'];
        $this->relationType = $data['type'];
    }
}
