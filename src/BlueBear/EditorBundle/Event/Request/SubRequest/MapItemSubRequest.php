<?php

namespace BlueBear\EditorBundle\Event\Request\SubRequest;

use JMS\Serializer\Annotation as Serializer;

class MapItemSubRequest
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
