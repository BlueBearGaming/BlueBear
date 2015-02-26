<?php

namespace BlueBear\EngineBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * ErrorResponse
 *
 * Event in error response
 *
 * @AccessorOrder("custom", custom={"code", "timestamp", "type", "message", "data", "eventRequest", "stackTrace"})
 */
class ErrorResponse extends EventResponse
{
    public $message;

    public $stackTrace;

    public $eventRequest;
}