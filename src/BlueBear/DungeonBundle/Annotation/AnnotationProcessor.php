<?php

namespace BlueBear\DungeonBundle\Annotation;

use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\DungeonBundle\UnitOfWork\EntityReferenceCollection;
use BlueBear\DungeonBundle\UnitOfWork\Relation;
use BlueBear\DungeonBundle\UnitOfWork\UnitOfWork;
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
        // create entity from configuration
        foreach ($entityData['data'] as $id => $entityData) {
            $entity = new $class;
            $accessor->setValue($entity, $idProperty, $id);

            if ($entityData) {
                // insert dynamic data
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

                        $this->registeredRelations[$class][] = new Relation(
                            $attributeName,
                            $relation->relationClass,
                            $relation->relationType,
                            $entity,
                            $attributeData
                        );
                    }
                }
            }
            $this->unitOfWork->add($entity);
        }
    }

    public function processRelations()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->registeredRelations as $classRelations) {
            /** @var Relation $relation */
            foreach ($classRelations as $relation) {
                $data = $relation->getData();
                $owner = $relation->getOwner();
                $idProperty = $this->unitOfWork->getIdProperty($relation->getRelationClass());

                if ($relation->getRelationType() == Relation::RELATION_ONE_TO_ONE) {
                    // if provided data is a string, we create an entity reference and we check if it has been registered
                    // in unit of work
                    $reference = new EntityReference($relation->getRelationClass(), $data);
                    // if entity is not registered, an exception will be thrown
                    $this->unitOfWork->load($reference);
                    // setting entity reference
                    $accessor->setValue($owner, $relation->getAttributeName(), $reference);

                } else if ($relation->getRelationType() == Relation::RELATION_ONE_TO_MANY) {
                    $ownerRelationsCollection = new EntityReferenceCollection($relation->getRelationClass());

                    foreach ($data as $entityDataIdentifier => $entityData) {
                        $class = $relation->getRelationClass();
                        $entity = new $class;

                        if ($entityData && is_array($entityData)) {
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
                        $ownerRelationsCollection->add(new EntityReference(
                            $relation->getRelationClass(),
                            $accessor->getValue($entity, $idProperty)
                        ));
                    }
                    $accessor->setValue($owner, $relation->getAttributeName(), $ownerRelationsCollection);
                } else {
                    throw new Exception("Relation of type {$relation->getRelationType()} are not handled : ");
                }
            }
        }
    }
}
