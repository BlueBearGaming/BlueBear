<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class CombatRequest extends EventRequest
{
    /**
     * Map context id
     *
     * @Expose()
     * @Type("integer")
     */
    public $gameId;
}
