<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class MapItemClickRequest extends EventRequest
{
    /**
     * TODO remove this, use source instead
     *
     * @Expose()
     * @Type("integer")
     */
    public $x;

    /**
     * TODO remove this, use source instead
     *
     * @Expose()
     * @Type("integer")
     */
    public $y;

    /**
     * @Expose()
     * @Type("integer")
     */
    public $pencil;

    /**
     * @Expose()
     * @Type("integer")
     */
    public $layer;

    /**
     * Map item source
     *
     * @var MapItemSubRequest
     * @Expose()
     * @Type("BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest")
     */
    public $source;

    /**
     * Map item target (in case of entity movement)
     *
     * @Expose()
     * @Type("BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest")
     * @var MapItemSubRequest
     */
    public $target;
}
