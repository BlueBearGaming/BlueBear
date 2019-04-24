<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use JMS\Serializer\Annotation as Serializer;

class UserContextSubRequest 
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("App\Utils\Position")
     */
    public $viewCenter;
}
