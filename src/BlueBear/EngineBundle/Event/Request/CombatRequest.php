<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation as Serializer;

class CombatRequest extends EventRequest
{
    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $gameId;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $contextId;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("array<integer, BlueBear\EngineBundle\Event\Sub\FightersList>")
     */
    public $fightersByPlayer = [];
}
