<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use BlueBear\CoreBundle\Utils\Position;
use JMS\Serializer\Annotation as Serializer;

class LoadContextSubRequest 
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     * @var Position
     */
    public $topLeft;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     * @var Position
     */
    public $bottomRight;
}