<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use JMS\Serializer\Annotation as Serializer;

class MapUpdateItemSubRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $x;

    /**
     * @Serializer\Type("integer")
     */
    public $y;

    /**
     * @Serializer\Type("string")
     */
    public $pencilName;

    /**
     * @Serializer\Type("string")
     */
    public $layerName;
}
