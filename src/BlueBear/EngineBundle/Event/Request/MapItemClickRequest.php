<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class MapItemClickRequest extends EventRequest
{
    /**
     * @Expose()
     * @Type("integer")
     */
    public $x;

    /**
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
     * Name of the map item target
     *
     * @Expose()
     * @Type("string")
     */
    public $target;

    /**
     * Name of the map item source (in case of entity moving)
     *
     * @Expose()
     * @Type("string")
     */
    public $source;
}
