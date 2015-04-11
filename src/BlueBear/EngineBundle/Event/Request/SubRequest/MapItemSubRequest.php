<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use BlueBear\CoreBundle\Utils\Position;
use JMS\Serializer\Annotation as Serializer;

class MapItemSubRequest
{
    /**
     * Map item position (x, y coordinates)
     *
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     * @var Position
     */
    public $position;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $layer;
}
