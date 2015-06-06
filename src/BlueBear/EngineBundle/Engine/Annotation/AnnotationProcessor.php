<?php

namespace BlueBear\EngineBundle\Engine\Annotation;

use BlueBear\EngineBundle\Engine\UnitOfWork\UnitOfWork;
use Exception;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Parser;

class AnnotationProcessor
{
    protected $metadataFactory;

    protected $unitOfWork;

    protected $registeredRelations = [];

    public function __construct(MetadataFactoryInterface $metadataFactory, UnitOfWork $unitOfWork)
    {
        $this->metadataFactory = $metadataFactory;
        $this->unitOfWork = $unitOfWork;
    }

    public function process(array $entityData)
    {
        $class = $entityData['class'];
        $classMetadata = $this->metadataFactory->getMetadataForClass($class);
        $accessor = PropertyAccess::createPropertyAccessor();
        $idProperty = null;
        $relations = [];

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            /* @var IdMetadata $propertyMetadata */
            if (isset($propertyMetadata->idProperty)) {
                $idProperty = $propertyMetadata->idProperty;
            }
            /* @var RelationMetadata $propertyMetadata */
            if (isset($propertyMetadata->relationClass)) {
                $relations[$propertyMetadata->name] = $propertyMetadata;
            }
        }
        if (!$idProperty) {
            throw new Exception("Game entity {$class} should have an identifier");
        }
        // class has an id property, it can be registered in unit of work
        $this->unitOfWork->registerClass($class, $idProperty);

        if (!is_array($entityData['data'])) {
            return;
        }
        foreach ($entityData['data'] as $id => $entityData) {
            $entity = new $class;
            $accessor->setValue($entity, $idProperty, $id);

            foreach ($entityData as $attributeName => $attributeData) {

                if (!array_key_exists($attributeName, $relations)) {
                    if (is_array($attributeData)) {
                        $accessor->setValue($entity, $attributeName, $attributeData);
                    } else if (is_string($attributeData)) {
                        $accessor->setValue($entity, $attributeName, $attributeData);
                    } else {
                        throw new Exception('Not handled parse type : ' . print_r($attributeData));
                    }
                } else {
                    /** @var RelationMetadata $relation */
                    $relation = $relations[$attributeName];

                    $this->registeredRelations[$class][] = [
                        'attribute' => $attributeName,
                        'relationClass' =>  $relation->relationClass,
                        'data' => $attributeData
                    ];
                }
            }
            $this->unitOfWork->add($entity);
        }
    }

    public function processRelations()
    {
        //var_dump($this->registeredRelations);
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->registeredRelations as $classRelations) {

            foreach ($classRelations as $relation) {
                //var_dump($relation);
                if (isset($relation['data'])) {
                    if (!is_array($relation['data'])) {
                        $relation['data'] = [$relation['data']];
                    }

                    foreach ($relation['data'] as $entityData) {
                        $entity = new $relation['relationClass'];

                        foreach ($entityData as $attributeName => $attributeData) {
                            if (is_array($attributeData)) {
                                $accessor->setValue($entity, $attributeName, $attributeData);
                            } else if (is_string($attributeData) or is_numeric($attributeData)) {
                                $accessor->setValue($entity, $attributeName, $attributeData);
                            } else {
                                throw new Exception('Not handled parse type : ' . print_r($attributeData));
                            }
                        }
                        $this->unitOfWork->add($entity);
                    }
                }
            }
        }
    }


}
