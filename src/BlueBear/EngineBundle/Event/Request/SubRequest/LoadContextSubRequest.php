<?php

namespace BlueBear\EngineBundle\Event\Request\SubRequest;

use JMS\Serializer\Annotation as Serializer;

class LoadContextSubRequest 
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     */
    public $topLeft;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\CoreBundle\Utils\Position")
     */
    public $bottomRight;
}