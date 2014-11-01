<?php

namespace BlueBear\EngineBundle\Event\Tile;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TileLeaveSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_BEFORE_LEAVE => 'onBeforeLeave',
            EngineEvent::ENGINE_ON_LEAVE => [
                'onLeave'
            ],
            EngineEvent::ENGINE_ON_AFTER_LEAVE => [
                'onAfterLeave'
            ]
        ];
    }

    public function onBeforeLeave()
    {

    }

    public function onLeave()
    {

    }
}