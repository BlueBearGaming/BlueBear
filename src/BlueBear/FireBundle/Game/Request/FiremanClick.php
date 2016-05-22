<?php

namespace BlueBear\FireBundle\Game\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\EventRequestInterface;
use JMS\Serializer\Annotation as Serializer;

class FiremanClick extends EventRequest implements EventRequestInterface
{
    /**
     * @Serializer\Type("integer")
     */
    public $firemanId;

    /**
     * @Serializer\Type("integer")
     */
    public $destinationX;

    /**
     * @Serializer\Type("integer")
     */
    public $destinationY;
}
