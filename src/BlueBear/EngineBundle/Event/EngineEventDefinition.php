<?php

namespace BlueBear\EngineBundle\Event;


/**
 * Describe a GameEvent. Contains the fully-qualified namespace for the event request class, event response class
 * and the event class itself
 */
class EngineEventDefinition
{
    /**
     * Event name
     *
     * @var string
     */
    protected $eventName;

    /**
     * Event Request class
     *
     * @var string
     */
    protected $requestClass;

    /**
     * Event response class
     *
     * @var string
     */
    protected $responseClass;

    /**
     * Event class
     *
     * @var string
     */
    protected $eventClass;

    /**
     * EngineEventDefinition constructor.
     *
     * @param string $eventName
     * @param string $requestClass
     * @param string $responseClass
     * @param string $eventClass
     */
    public function __construct($eventName, $requestClass, $responseClass, $eventClass)
    {
        $this->eventName = $eventName;
        $this->requestClass = $requestClass;
        $this->responseClass = $responseClass;
        $this->eventClass = $eventClass;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @return string
     */
    public function getRequestClass()
    {
        return $this->requestClass;
    }

    /**
     * @return string
     */
    public function getResponseClass()
    {
        return $this->responseClass;
    }

    /**
     * @return null|string
     */
    public function getEventClass()
    {
        return $this->eventClass;
    }
}
