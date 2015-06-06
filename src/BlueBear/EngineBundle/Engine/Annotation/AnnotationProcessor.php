<?php

namespace BlueBear\EngineBundle\Engine\Annotation;

use Exception;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Parser;

class AnnotationProcessor
{
    protected $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function process()
    {
        $parse = new Parser();
        $data = $parse->parse(file_get_contents(__DIR__ . '/../../Resources/data/race.yml'));
        $accessor = PropertyAccess::createPropertyAccessor();
        $class = $data['class'];

        $classMetadata = $this->metadataFactory->getMetadataForClass($class);

        //var_dump($classMetadata);
        $idProperty = null;
        $relations = [];
        $entities = [];

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
        foreach ($data['data'] as $id => $entityData) {
            $object = new $class;
            $accessor->setValue($object, $idProperty, $id);

            foreach ($entityData as $attributeName => $attributeData) {

                if (!array_key_exists($attributeName, $relations)) {
                    if (is_array($attributeData)) {
                        $accessor->setValue($object, $attributeName, $attributeData);
                    } else if (is_string($attributeData)) {
                        $accessor->setValue($object, $attributeName, $attributeData);
                    } else {
                        throw new Exception('Not handled parse type : ' . print_r($attributeData));
                    }
                }
            }
            $entities[$id] = $object;
        }
        var_dump($entities);


        die('panda!');
    }
}
