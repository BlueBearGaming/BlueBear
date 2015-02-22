<?php

namespace BlueBear\EditorBundle\Event\Map;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use ContainerTrait, HasException;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::EDITOR_MAP_PUT_PENCIL => 'onPutPencil'
        ];
    }

    public function onPutPencil(EngineEvent $event)
    {
        /** @var PutPencilRequest $request */
        $request = $event->getRequest();
        // check if id are provided
        $this->throwUnless($request->pencilId, 'Invalid pencil id');
        $this->throwUnless($request->layerId, 'Invalid layer id');
        /**
         * @var Layer $layer
         * @var Pencil $pencil
         */
        $layer = $this->getContainer()->get('bluebear.manager.layer')->find($request->layerId);
        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->find($request->pencilId);
        // check if layer is allowed for pencil
        $this->throwUnless($pencil, 'Pencil not found');
        $this->throwUnless($layer, 'Layer not found');
        $this->throwUnless($pencil->isLayerTypeAllowed($layer->getType()), 'Unauthorized layer for pencil');

        // removing existing map item at this position in this layer
        $mapItemManager = $this->getContainer()->get('bluebear.manager.map_item');
        $mapItem = $mapItemManager->findByPositionPencilAndLayer(new Position($request->x, $request->y), $pencil, $layer);

        if ($mapItem) {
            $mapItemManager->delete($mapItem);
        } else {
            // creating map item
            $mapItem = new MapItem();
            $mapItem->setX($request->x);
            $mapItem->setY($request->y);
            $mapItem->setLayer($layer);
            $mapItem->setPencil($pencil);
            $mapItem->setContext($event->getContext());
            $mapItemManager->save($mapItem);
        }
    }
}