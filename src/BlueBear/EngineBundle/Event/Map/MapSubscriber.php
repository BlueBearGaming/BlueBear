<?php

namespace BlueBear\EngineBundle\Event\Map;

use BlueBear\EngineBundle\Behavior\HasContextFactory;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use HasContextFactory;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_LOAD => 'onMapLoad',
            EngineEvent::ENGINE_ON_MAP_SAVE => 'onMapSave'
        ];
    }

    public function onMapLoad(EngineEvent $event)
    {
        $map = $event->getMap();

        if (!$map->getCurrentContext()) {
            $this->getContextFactory()->create($map);
        }
    }

    public function onMapSave(EngineEvent $event)
    {
        $this->check($event);

    }

    /**
     * Check if the map is loaded
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    protected function check(EngineEvent $event)
    {
        if (!$event->getMap()) {
            throw new Exception('Map should be loaded in map events');
        }
    }
}