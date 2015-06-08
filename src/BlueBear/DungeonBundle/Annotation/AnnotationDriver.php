<?php

namespace BlueBear\DungeonBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;
use Metadata\PropertyMetadata;
use ReflectionClass;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @param ReflectionClass $class
     *
     * @return ClassMetadata
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getProperties() as $reflectionProperty) {
            $propertyMetadata = new PropertyMetadata($class->getName(), $reflectionProperty->getName());

            $annotation = $this->reader->getPropertyAnnotation(
                $reflectionProperty,
                Id::class
            );
            if ($annotation) {
                /** @var IdMetadata $propertyMetadata */
                $propertyMetadata->idProperty = $propertyMetadata->name;
            }
            $classMetadata->addPropertyMetadata($propertyMetadata);

            /** @var Relation $annotation */
            $annotation = $this->reader->getPropertyAnnotation(
                $reflectionProperty,
                Relation::class
            );
            if ($annotation) {
                /** @var RelationMetadata $propertyMetadata */
                $propertyMetadata->relationClass = $annotation->relationClass;
                $propertyMetadata->relationType = $annotation->relationType;
            }
        }
        return $classMetadata;
    }

    public function setReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
