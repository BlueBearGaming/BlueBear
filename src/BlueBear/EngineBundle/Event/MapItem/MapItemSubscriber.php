<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\CoreBundle\Entity\Behavior\HasContainer;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemSubscriber implements EventSubscriberInterface
{
    use HasContainer;

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
        /** @var MapItemClickRequest $request */
        $request = $event->getRequest();
        // check data
        if (!$request->pencil) {
            throw new Exception('Invalid pencil id');
        }
        if (!$request->layer) {
            throw new Exception('Invalid layer id');
        }
        $layer = $this->getContainer()->get('bluebear.manager.layer')->find($request->layer);
        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->find($request->pencil);

        // set generic response
        $event->setResponse(new MapItemClickResponse());
    }
}