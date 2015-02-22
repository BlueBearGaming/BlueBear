<?php

namespace BlueBear\EditorBundle\Event\Map;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
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

    /**
     * Create a map item on layer with a pencil. If this item already exists, it will be removed
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onPutPencil(EngineEvent $event)
    {
        /** @var PutPencilRequest $request */
        $request = $event->getRequest();
        // check if id are provided
        $this->throwUnless($request->pencilName, 'Invalid pencil name');
        $this->throwUnless($request->layerName, 'Invalid layer name');
        /**
         * @var Layer $layer
         * @var Pencil $pencil
         */
        $layer = $this->getContainer()->get('bluebear.manager.layer')->findOneByName($request->layerName);
        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->findOneByName($request->pencilName);
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