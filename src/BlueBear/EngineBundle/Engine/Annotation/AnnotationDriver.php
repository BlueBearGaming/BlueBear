<?php

namespace BlueBear\EngineBundle\Engine\Annotation;

use Doctrine\Common\Annotations\Reader;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use ReflectionClass;

class AnnotationDriver implements DriverInterface
{
    protected $reader;

    /**
     * @param ReflectionClass $class
     *
     * @return ClassMetadata
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {

        die('panda');
    }

    public function setReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
