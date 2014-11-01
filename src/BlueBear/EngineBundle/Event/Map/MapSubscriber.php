<?php

namespace BlueBear\EngineBundle\Event\Map;

use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_LOAD => 'onMapLoad'
        ];
    }

    public function onMapLoad(EngineEvent $event)
    {
        if (!$event->getMap()) {
            throw new Exception('Map should be loaded in map events');
        }
    }
}