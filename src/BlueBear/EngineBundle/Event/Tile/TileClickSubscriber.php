<?php

namespace BlueBear\EngineBundle\Event\Tile;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TileClickSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_TILE_CLICK => [
                'onClick'
            ]
        ];
    }

    public function onClick(EngineEvent $event)
    {
        if (!is_numeric($event->getData()->x) or !is_numeric($event->getData()->x)) {
            throw new \Exception('Invalid tile coordinates');
        }
    }
}