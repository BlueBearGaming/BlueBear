<?php

namespace BlueBear\EditorBundle\Event\Map;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class PutPencilRequest extends EventRequest
{
    /**
     * Layer on which pencil has been painted
     *
     * @Expose()
     * @Type("string")
     */
    public $layerName;

    /**
     * Painted pencil
     *
     * @Expose()
     * @Type("string")
     */
    public $pencilName;

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
}