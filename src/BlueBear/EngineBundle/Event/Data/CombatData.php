<?php

namespace BlueBear\EngineBundle\Event\Data;

use JMS\Serializer\Annotation as Serializer;

class CombatData
{
    /**
     * Source entity instance id
     *
     * @Serializer\Expose()
     * @Serializer\Type("BlueBear\EngineBundle\Entity\EntityInstance")
     */
    public $source;

    /**
     * Available attacks for source entity instance
     *
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    public $attacks = [];

    /**
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    public $targets = [];

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $turn = 0;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $gameId = 0;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    public $contextId = 0;
}
