<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class AttackRequest extends EventRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $attackerId;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $defenderId;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    public $attackType;
}
