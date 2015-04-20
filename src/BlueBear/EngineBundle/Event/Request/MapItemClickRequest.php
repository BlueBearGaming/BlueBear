<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class MapItemClickRequest extends EventRequest
{
    /**
     * Map item source (can be optional. Set in case of entity movement)
     *
     * @var MapItemSubRequest
     * @Expose()
     * @Type("BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest")
     */
    public $source;

    /**
     * Map item target (required)
     *
     * @Expose()
     * @Type("BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest")
     * @var MapItemSubRequest
     */
    public $target;
}
