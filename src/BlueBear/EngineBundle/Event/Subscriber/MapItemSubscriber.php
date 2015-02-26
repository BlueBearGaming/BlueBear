<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemSubscriber implements EventSubscriberInterface
{
    use ContainerTrait, HasException;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_ITEM_CLICK => [
                'onClick'
            ]
        ];
    }

    /**
     * Default on click response
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onClick(EngineEvent $event)
    {

    }
}