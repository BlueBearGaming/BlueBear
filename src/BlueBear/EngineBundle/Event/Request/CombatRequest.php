<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class CombatRequest extends EventRequest
{
    /**
     * Game id
     *
     * @Expose()
     * @Type("integer")
     */
    public $gameId;

    /**
     * Entity instance id
     *
     * @Expose()
     * @Type("integer")
     */
    public $entityInstanceId;

    /**
     * Current turn number
     *
     * @Expose()
     * @Type("integer")
     */
    public $turn;
}
