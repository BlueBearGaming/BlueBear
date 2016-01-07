<?php

namespace BlueBear\EngineBundle\Event;


use Exception;

class EngineEventDefinition
{
    protected $eventName;
    protected $requestClass;
    protected $responseClass;

    public function __construct($eventName, $requestClass, $responseClass)
    {
        // check request class validity
        if (!class_exists($requestClass)) {
            throw new Exception("Invalid request class \"{$requestClass}\" (not found). Check your configuration");
        }
        if (!is_subclass_of($requestClass, 'BlueBear\EngineBundle\Event\EventRequest')) {
            throw new Exception("{$requestClass} should extend BlueBear\\EngineBundle\\Event\\EventRequest");
        }
        // check response class validity
        if (!class_exists($responseClass)) {
            throw new Exception("Event response class {$responseClass} not found");
        }
        if (!is_subclass_of($responseClass, 'BlueBear\EngineBundle\Event\EventResponse')) {
            throw new Exception("{$responseClass} should extend BlueBear\\EngineBundle\\Event\\EventResponse");
        }
        $this->eventName = $eventName;
        $this->requestClass = $requestClass;
        $this->responseClass = $responseClass;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @return mixed
     */
    public function getRequestClass()
    {
        return $this->requestClass;
    }

    /**
     * @return mixed
     */
    public function getResponseClass()
    {
        return $this->responseClass;
    }
}
