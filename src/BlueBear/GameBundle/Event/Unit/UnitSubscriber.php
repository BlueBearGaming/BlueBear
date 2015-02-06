<?php

namespace BlueBear\GameBundle\Event\Unit;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UnitSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_PUT_UNIT => [
                'putUnit'
            ]
        ];
    }

    public function putUnit(EngineEvent $event)
    {
        $request = $event->getRequest();
    }
}