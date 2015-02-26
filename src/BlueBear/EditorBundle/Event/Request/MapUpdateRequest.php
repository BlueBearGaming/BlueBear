<?php

namespace BlueBear\EditorBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class MapUpdateRequest extends EventRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("array<BlueBear\EditorBundle\Event\Map\MapItemSubRequest>")
     */
    public $mapItems;
}