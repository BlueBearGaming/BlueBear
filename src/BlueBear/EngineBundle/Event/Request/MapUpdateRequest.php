<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class MapUpdateRequest extends EventRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("array<BlueBear\EngineBundle\Event\Request\SubRequest\MapUpdateItemSubRequest>")
     */
    public $mapItems;
}
