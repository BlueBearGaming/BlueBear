<?php

namespace BlueBear\EngineBundle\Event\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Behavior\HasMapManager;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EngineSubscriber implements EventSubscriberInterface
{
    use HasEventDispatcher, HasMapManager;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_ENGINE_EVENT => 'onEngineEvent'
        ];
    }

    /**
     * On each engine event, we should load the map
     *
     * @param EngineEvent $event
     * @return EngineEvent
     * @throws Exception
     */
    public function onEngineEvent(EngineEvent $event)
    {
        $eventData = $event->getData();

        if (!$eventData->mapId) {
            throw new Exception('Empty mapId : ' . $eventData->mapId);
        }
        /** @var Map $map */
        $map = $this->getMapManager()->find($eventData->mapId);

        if (!$map) {
            throw new Exception('Map not found');
        }
        $event->setMap($map);
        // dispatch map load event
        $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_MAP_LOAD, $event);

        return $event;
    }
}