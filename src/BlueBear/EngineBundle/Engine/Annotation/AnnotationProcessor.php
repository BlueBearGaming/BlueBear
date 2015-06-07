<?php

namespace BlueBear\EngineBundle\Engine\Annotation;

use BlueBear\EngineBundle\Engine\UnitOfWork\EntityReference;
use BlueBear\EngineBundle\Engine\UnitOfWork\EntityReferenceCollection;
use BlueBear\EngineBundle\Engine\UnitOfWork\UnitOfWork;
use Exception;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class AnnotationProcessor
 *
 * Process annotation for game unit of work
 */
class AnnotationProcessor
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    /**
     * @var array
     */
    protected $registeredRelations = [];

    /**
     * Initialize processor with unit of work and metadata factory
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param UnitOfWork $unitOfWork
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, UnitOfWork $unitOfWork)
    {
        $this->metadataFactory = $metadataFactory;
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * Register classes and load data in the unit of work
     *
     * @param array $entityData
     * @throws Exception
     */
    public function process(array $entityData)
    {
        // entity class
        $class = $entityData['class'];
        $classMetadata = $this->metadataFactory->getMetadataForClass($class);
        $accessor = PropertyAccess::createPropertyAccessor();
        $idProperty = null;
        $relations = [];

        // process annotations id and relation
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

        if (!array_key_exists('data', $entityData) || !is_array($entityData['data'])) {
            return;
        }
        // insert dynamic data
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
                        'relationClass' => $relation->relationClass,
                        'data' => $attributeData,
                        'owner' => $entity
                    ];
                }
            }
            $this->unitOfWork->add($entity);
        }
    }

    public function processRelations()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->registeredRelations as $classRelations) {

            foreach ($classRelations as $relation) {
                //var_dump($relation);
                if (!array_key_exists('data', $relation)) {
                    continue;
                }
                if (!is_array($relation['data'])) {
                    $relation['data'] = [$relation['data']];
                }
                $owner = $relation['owner'];
                $attribute = $relation['attribute'];

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
                    $ownerRelationsCollection = $accessor->getValue($owner, $attribute);

                    if (!$ownerRelationsCollection) {
                        $ownerRelationsCollection = new EntityReferenceCollection($relation['relationClass']);
                    }
                    if (!($ownerRelationsCollection instanceof EntityReferenceCollection)) {
                        throw new Exception("Attribute {$attribute} should be an instance of EntityReferenceCollection");
                    }
                    $idProperty = $this->unitOfWork->getIdProperty($relation['relationClass']);
                    $ownerRelationsCollection->add(new EntityReference(
                        $relation['relationClass'],
                        $accessor->getValue($entity, $idProperty)
                    ));
                    $accessor->setValue($owner, $attribute, $ownerRelationsCollection);
                    $this->unitOfWork->add($entity);
                }

            }
        }
    }


}
