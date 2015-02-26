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
     * @Expose()
     * @Type("integer")
     */
    public $unit;
}