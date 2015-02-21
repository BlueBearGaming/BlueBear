<?php

namespace BlueBear\EngineBundle\Event;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * EventResponse
 *
 * @AccessorOrder("custom", custom={"code", "timestamp", "type", "data"})
 */
class EventResponse
{
    /**
     * Response class type ("LoadContextResponse" for example)
     *
     * @var string
     */
    public $type;

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

    public function __construct()
    {
        $this->type = get_class($this);
        $this->code = EngineEvent::ENGINE_EVENT_RESPONSE_OK;
        $this->timestamp = (int) date('U');
    }
}