<?php

namespace BlueBear\EngineBundle\Engine\Annotation;


class Relation
{
    protected $class;

    public function __construct(array $data)
    {
        $this->class = $data['class'];
    }
}
