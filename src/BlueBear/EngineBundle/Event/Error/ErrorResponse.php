<?php

namespace BlueBear\EngineBundle\Event\Error;

use BlueBear\EngineBundle\Event\EventResponse;

/**
 * ErrorResponse
 *
 * Event in error response
 */
class ErrorResponse extends EventResponse
{
    public $message;

    public $stackTrace;
}