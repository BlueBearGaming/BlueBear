<?php

namespace BlueBear\GameBundle\Event\Unit;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class PutUnitRequest extends EventRequest
{
    /**
     * Unit id
     *
     * @Expose()
     * @Type("integer")
     * @SerializedName("unitId")
     */
    public $unitId;

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