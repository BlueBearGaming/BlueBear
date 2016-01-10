<?php

namespace BlueBear\FireBundle\Event\Subscriber;


use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovementSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'bluebear.fire.move' => 'move'
        ];
    }

    public function move(EngineEvent $event)
    {
        $response = $event->getResponse();
        $response->setData([
            'x' => 1,
            'y' => 0
        ]);
    }
}
