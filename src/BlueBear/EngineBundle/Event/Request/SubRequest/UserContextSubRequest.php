<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use JMS\Serializer\Annotation as Serializer;

class UserContextSubRequest 
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     */
    public $viewCenter;
}