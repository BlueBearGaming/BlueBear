<?php

namespace BlueBear\EngineBundle\Engine\Annotation;

/**
 * @Annotation
 */
class Relation
{
    public $relationClass;

    public function __construct(array $data)
    {
        $this->relationClass = $data['class'];
    }
}
