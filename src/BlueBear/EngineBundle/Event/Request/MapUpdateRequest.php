<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapUpdateItemSubRequest;
use JMS\Serializer\Annotation as Serializer;

class MapUpdateRequest extends EventRequest
{
    /**
     * @var MapUpdateItemSubRequest[]
     *
     * @Serializer\Expose()
     * @Serializer\Type("array<BlueBear\EngineBundle\Event\Request\SubRequest\MapUpdateItemSubRequest>")
     */
    public $mapItems;
}
