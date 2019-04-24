<?php

namespace BlueBear\EngineBundle\Serialization\Exclusion;

use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;

class ExclusionStrategy implements ExclusionStrategyInterface
{

    /**
     * Whether the class should be skipped.
     *
     * @param ClassMetadata $metadata
     * @param Context $context
     * @return bool
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
        // TODO: Implement shouldSkipClass() method.
    }

    /**
     * Whether the property should be skipped.
     *
     * @param PropertyMetadata $property
     * @param Context $context
     * @return bool
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        // TODO: Implement shouldSkipProperty() method.
    }
}