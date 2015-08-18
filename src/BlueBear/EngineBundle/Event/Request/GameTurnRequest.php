<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class GameTurnRequest extends EventRequest
{
    /**
     * Source entity instance id
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $sourceId;

    /**
     * Available attacks for source entity instance
     *
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    public $attacks = [];

    /**
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    public $targetsIds = [];

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $turn;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $gameId = 0;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $contextId = 0;
}
