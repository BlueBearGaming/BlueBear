<?php

namespace BlueBear\EditorBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class PutEntityRequest extends EventRequest
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
     * @Type("integer")
     */
    public $entityModelId;

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