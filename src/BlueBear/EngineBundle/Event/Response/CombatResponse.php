<?php

namespace BlueBear\EngineBundle\Event\Response;

use BlueBear\EngineBundle\Event\EventResponse;
use JMS\Serializer\Annotation as Serializer;

class CombatResponse extends EventResponse
{
    public $endPoint;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\EngineBundle\Event\Data\CombatData")
     */
    public $data;
}
