<?php

namespace BlueBear\EngineBundle\Event;


use Exception;

class EngineEventDefinition
{
    protected $eventName;
    protected $requestClass;
    protected $responseClass;

    /**
     * @var null|string
     */
    protected $eventClass;

    /**
     * EngineEventDefinition constructor.
     *
     * @param $eventName
     * @param $requestClass
     * @param $responseClass
     * @param $eventClass
     * @throws Exception
     */
    public function __construct($eventName, $requestClass, $responseClass, $eventClass)
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
        // check event class validity
        if (!class_exists($eventClass)) {
            throw new Exception("Event class {$eventClass} not found");
        }
        $this->eventName = $eventName;
        $this->requestClass = $requestClass;
        $this->responseClass = $responseClass;
        $this->eventClass = $eventClass;
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
