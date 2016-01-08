<?php

namespace BlueBear\EngineBundle\Event;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * EventResponse
 *
 * @AccessorOrder("custom", custom={"code", "uid", "timestamp", "name", "data"})
 */
class EventResponse
{
    /**
     * Response code (KO or OK)
     *
     * @var string
     */
    public $code;

    /**
     * Unique ID
     *
     * @var string
     */
    public $uid;

    /**
     * Response date (timestamp)
     *
     * @var int
     */
    public $timestamp;

    /**
     * Response class name ("bluebear.game.myEvent" for example)
     *
     * @var string
     */
    public $name;

    /**
     * Response data. This property is protected because you should implement a setData method to organize data part of
     * the response
     *
     * @var mixed
     */
    protected $data;

    /**
     * EventResponse constructor.
     * @param $eventName
     */
    public function __construct($eventName)
    {
        $this->name = $eventName;
        $this->code = EngineEvent::ENGINE_EVENT_RESPONSE_OK;
        $this->timestamp = (int) date('U');
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
        $this->uid = uniqid('bluebear');
    }
}
