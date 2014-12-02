<?php

namespace BlueBear\EngineBundle\Event;

use BlueBear\CoreBundle\Entity\Behavior\Data;
use BlueBear\CoreBundle\Entity\Map\Map;
use Symfony\Component\EventDispatcher\Event;

class EngineEvent extends Event
{
    use Data;

    protected $eventName;

    /**
     * @var Map $map
     */
    protected $map;

    /**
     * Engine events
     */
    const ENGINE_ON_ENGINE_EVENT = 'bluebear.engine.onEngineEvent';

    /**
     * Map events
     */
    const ENGINE_ON_MAP_LOAD = 'bluebear.engine.onMapLoad';
    const ENGINE_ON_MAP_SAVE = 'bluebear.engine.onMapSave';

    /**
     * Tile events
     */
    const ENGINE_ON_BEFORE_LEAVE = 'bluebear.engine.onBeforeLeave';
    const ENGINE_ON_LEAVE = 'bluebear.engine.onLeave';
    const ENGINE_ON_AFTER_LEAVE = 'bluebear.engine.onAfterLeave';
    const ENGINE_ON_BEFORE_ENTER = 'bluebear.engine.onBeforeEnter';
    const ENGINE_ON_ENTER = 'bluebear.engine.onEnter';
    const ENGINE_ON_AFTER_ENTER = 'bluebear.engine.onAfterEnter';
    const ENGINE_ON_TILE_CLICK = 'bluebear.engine.onTileClick';

    /**
     * Return allowed events to be called in front
     *
     * @return array
     */
    public static function getAllowedEvents()
    {
        return [
            self::ENGINE_ON_TILE_CLICK,
            self::ENGINE_ON_MAP_LOAD,
            self::ENGINE_ON_MAP_SAVE
        ];
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param mixed $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }
}