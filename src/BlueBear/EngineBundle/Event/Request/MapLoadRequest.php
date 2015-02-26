<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class MapLoadRequest extends EventRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\EngineBundle\Event\Request\SubRequest\UserContextSubRequest")
     */
    public $userContext;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\EngineBundle\Event\Request\SubRequest\LoadContextSubRequest")
     */
    public $loadContext;
}