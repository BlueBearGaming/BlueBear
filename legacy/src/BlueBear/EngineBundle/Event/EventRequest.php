<?php

namespace BlueBear\EngineBundle\Event;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * EventRequest
 *
 * Generic event request
 */
class EventRequest
{
    /**
     * Map context id
     *
     * @Expose()
     * @Type("integer")
     */
    public $contextId;
}