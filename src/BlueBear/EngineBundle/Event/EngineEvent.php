<?php

namespace BlueBear\EngineBundle\Event;

use BlueBear\CoreBundle\Entity\Behavior\Data;
use Symfony\Component\EventDispatcher\Event;

class EngineEvent extends Event
{
    use Data;

    /**
     * Leaving tile events
     */
    const ENGINE_ON_BEFORE_LEAVE = 'bluebear.engine.onBeforeLeave';
    const ENGINE_ON_LEAVE = 'bluebear.engine.onLeave';
    const ENGINE_ON_AFTER_LEAVE = 'bluebear.engine.onAfterLeave';
    /**
     * Entering tiles events
     */
    const ENGINE_ON_BEFORE_ENTER = 'bluebear.engine.onBeforeEnter';
    const ENGINE_ON_ENTER = 'bluebear.engine.onEnter';
    const ENGINE_ON_AFTER_ENTER = 'bluebear.engine.onAfterEnter';

    public static function getAllowedEvents()
    {
        return [
            self::ENGINE_ON_BEFORE_LEAVE,
            self::ENGINE_ON_LEAVE,
            self::ENGINE_ON_AFTER_LEAVE,
            self::ENGINE_ON_BEFORE_ENTER,
            self::ENGINE_ON_ENTER,
            self::ENGINE_ON_AFTER_ENTER,
        ];
    }
}