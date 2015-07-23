<?php

namespace BlueBear\EngineBundle\Event\Sub;

use BlueBear\EngineBundle\Entity\EntityInstance;
use JMS\Serializer\Annotation as Serializer;

class FightersList
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("array<BlueBear\EngineBundle\Entity\EntityInstance>")
     * @var EntityInstance[]
     */
    public $entityInstances = [];
}
