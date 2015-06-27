<?php

namespace BlueBear\EngineBundle\Event\Request;

use BlueBear\EngineBundle\Event\EventRequest;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

class CombatRequest extends EventRequest
{
    /**
     * Game id
     *
     * @Expose()
     * @Type("integer")
     */
    public $gameId;

    /**
     * On a unit turn, this is the id of the entity instance for this unit
     *
     * @Expose()
     * @Type("integer")
     */
    public $lockedEntityInstanceId;

    /**
     * Entity instance ids
     *
     * @Expose()
     * @Type("array")
     */
    public $entityInstanceIds;

    /**
     * Current turn number
     *
     * @Expose()
     * @Type("integer")
     */
    public $turn;
}
