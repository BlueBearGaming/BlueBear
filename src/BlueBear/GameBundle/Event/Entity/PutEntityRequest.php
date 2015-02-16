<?php

namespace BlueBear\GameBundle\Event\Entity;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class PutEntityRequest extends EventRequest
{
    /**
     * Unit id
     *
     * @Expose()
     * @Type("integer")
     * @SerializedName("entityModelId")
     */
    public $entityModelId;

    /**
     * Unit x position
     *
     * @Expose()
     * @Type("integer")
     */
    public $x;

    /**
     * Unit y position
     *
     * @Expose()
     * @Type("integer")
     */
    public $y;
}