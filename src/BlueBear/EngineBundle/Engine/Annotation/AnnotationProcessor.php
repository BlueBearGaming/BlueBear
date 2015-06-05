<?php

namespace BlueBear\EngineBundle\Engine\Annotation;


use Metadata\MetadataFactoryInterface;

class AnnotationProcessor
{
    protected $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function process()
    {
        // TODO load configuration
        die('panda');
    }
}
