<?php

namespace BlueBear\EngineBundle\Listener;

use BlueBear\EngineBundle\Factory\EntityTypeFactory;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class DoctrineEventListener
{
    /**
     * @var array
     */
    protected $mapping = [];

    const ENTITY_INSTANCE_CLASS = 'BlueBear\EngineBundle\Entity\EntityInstance';

    public function __construct(EntityTypeFactory $entityTypeFactory)
    {
        foreach ($entityTypeFactory->getEntityTypes() as $type) {
            if ($type->getClass()) {
                $this->mapping[$type->getName()] = $type->getClass();
            } else {
                $this->mapping[$type->getName()] = self::ENTITY_INSTANCE_CLASS;
            }
        }
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        /** @var ClassMetadataInfo $metadata */
        $metadata = $event->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if ($class === null) {
            $class = new \ReflectionClass($metadata->getName());
        }

        if ($class->getName() == self::ENTITY_INSTANCE_CLASS) {
            $metadata->setDiscriminatorMap($this->mapping);
        }
    }
}