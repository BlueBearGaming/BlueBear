<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemEnterSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_BEFORE_ENTER => [
                'onBeforeEnter'
            ],
            EngineEvent::ENGINE_ON_ENTER => [
                'onEnter'
            ],
            EngineEvent::ENGINE_ON_AFTER_ENTER => [
                'onAfterEnter'
            ]
        ];
    }
}