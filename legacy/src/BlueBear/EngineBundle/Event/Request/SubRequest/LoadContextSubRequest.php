<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use App\Utils\Position;
use JMS\Serializer\Annotation as Serializer;

class LoadContextSubRequest 
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("App\Utils\Position")
     * @var Position
     */
    public $topLeft;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("App\Utils\Position")
     * @var Position
     */
    public $bottomRight;
}
