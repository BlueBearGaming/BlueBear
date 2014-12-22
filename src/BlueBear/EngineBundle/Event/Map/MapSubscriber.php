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
        $data = $event->getData();

        // if a context id is provided, we try to load this context
        if (property_exists($data, 'contextId') and $data->contextId) {
            $this->getContextFactory()->loadContext($map, $data->contextId);
        }
        // if no context id we load the last context
        else if (!$map->getCurrentContext()) {
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