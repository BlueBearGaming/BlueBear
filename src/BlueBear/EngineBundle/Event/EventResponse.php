<?php

namespace BlueBear\EngineBundle\Event;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * EventResponse
 *
 * @AccessorOrder("custom", custom={"code", "timestamp", "name", "data"})
 */
class EventResponse
{
    /**
     * Response class name ("bluebear.game.myEvent" for example)
     *
     * @var string
     */
    public $name;

    /**
     * Response code (KO or OK)
     *
     * @var string
     */
    public $code;

    /**
     * Response data
     *
     * @var mixed
     */
    public $data;

    /**
     * Response date (timestamp)
     *
     * @var int
     */
    public $timestamp;

    public function __construct($eventName)
    {
        $this->name = $eventName;
        $this->code = EngineEvent::ENGINE_EVENT_RESPONSE_OK;
        $this->timestamp = (int) date('U');
    }
}