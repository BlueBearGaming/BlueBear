<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemClickSubscriber implements EventSubscriberInterface
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
        if (!is_numeric($event->getData()->x) or !is_numeric($event->getData()->x)) {
            throw new \Exception('Invalid mapItem coordinates');
        }
    }
}