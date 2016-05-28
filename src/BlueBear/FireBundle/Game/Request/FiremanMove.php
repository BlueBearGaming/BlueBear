<?php

namespace BlueBear\FireBundle\Game\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\EventRequestInterface;
use JMS\Serializer\Annotation as Serializer;

class FiremanMove extends EventRequest
{
    /**
     * @Serializer\Type("integer")
     */
    public $firemanId;

    /**
     * @Serializer\Type("integer")
     */
    public $sourceMapItemId;

    /**
     * @Serializer\Type("integer")
     */
    public $destinationMapItemId;
}
