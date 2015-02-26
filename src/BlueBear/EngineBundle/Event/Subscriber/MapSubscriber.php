<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\EngineBundle\Behavior\HasContextFactory;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Response\MapLoadResponse;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use HasContextFactory, ContainerTrait;

    /**
     * Subscribe on mapLoad, mapSave events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_LOAD => 'onMapLoad'
        ];
    }

    /**
     * Load context and return it into event response
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onMapLoad(EngineEvent $event)
    {
        /** @var MapLoadResponse $response */
        $response = $event->getResponse();
        $response->data = $event->getContext();
    }
}