<?php

namespace BlueBear\EditorBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;
use JMS\Serializer\Annotation as Serializer;

class MapUpdateResponse extends EventResponse
{
    /**
     * @Serializer\Expose()
     */
    public $updated = [];

    /**
     * @Serializer\Expose()
     */
    public $removed = [];
}