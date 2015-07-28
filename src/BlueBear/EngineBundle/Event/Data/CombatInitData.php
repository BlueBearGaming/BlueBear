<?php

namespace BlueBear\EngineBundle\Event\Data;

use JMS\Serializer\Annotation as Serializer;

class CombatInitData
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
    public $fightersByPlayer;
}
