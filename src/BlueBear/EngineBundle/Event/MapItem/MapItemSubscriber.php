<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_ITEM_CLICK => [
                'onClick'
            ]
        ];
    }

    public function onClick(EngineEvent $event)
    {
        $event->setResponse(new MapItemClickResponse());
    }
}