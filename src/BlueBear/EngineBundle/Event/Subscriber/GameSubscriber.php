<?php

namespace BlueBear\EngineBundle\Event\Subscriber;


use BlueBear\EngineBundle\Event\EngineEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class GameSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_GAME_CREATE => 'onGameCreate'
        ];
    }

    public function onGameCreate(EngineEvent $event)
    {

    }
}
