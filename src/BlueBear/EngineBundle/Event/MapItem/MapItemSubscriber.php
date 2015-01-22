<?php

namespace BlueBear\EngineBundle\Event\MapItem;

use BlueBear\CoreBundle\Entity\Behavior\HasContainer;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapItemSubscriber implements EventSubscriberInterface
{
    use HasContainer, HasException;

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
        // check if id are provided
        $this->throwUnless($request->pencil, 'Invalid pencil id');
        $this->throwUnless($request->layer, 'Invalid layer id');
        /**
         * @var Layer $layer
         * @var Pencil $pencil
         */
        $layer = $this->getContainer()->get('bluebear.manager.layer')->find($request->layer);
        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->find($request->pencil);
        // check if layer is allowed for pencil
        $this->throwUnless($pencil, 'Pencil not found');
        $this->throwUnless($layer, 'Layer not found');
        $this->throwUnless($pencil->isLayerTypeAllowed($layer->getType()), 'Unauthorized layer for pencil');

        // removing existing map item at this position in this layer
        $mapItemManager = $this->getContainer()->get('bluebear.manager.map_item');
        $mapItem = $mapItemManager->findByPositionAndLayer($request->x, $request->y, $layer);

        if ($mapItem) {
            $mapItemManager->delete($mapItem);
        }
        // creating map item
        $mapItem = new MapItem();
        $mapItem->setX($request->x);
        $mapItem->setY($request->y);
        $mapItem->setLayer($layer);
        $mapItem->setPencil($pencil);
        $mapItem->setContext($event->getContext());
        $mapItemManager->save($mapItem);

        // set generic response
        $event->setResponse(new MapItemClickResponse());
    }
}