<?php

namespace BlueBear\EditorBundle\Event\Map;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapItemManager;
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
            EngineEvent::EDITOR_MAP_PUT_PENCIL => 'onPutPencil',
            EngineEvent::EDITOR_MAP_UPDATE => 'onMapUpdate'
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
        $layer = $this->getContainer()->get('bluebear.manager.layer')->findOneBy([
            'name' => $request->layerName
        ]);
        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->findOneBy([
            'name' => $request->pencilName
        ]);
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

    public function onMapUpdate(EngineEvent $event)
    {
        /** @var MapUpdateRequest $request */
        $request = $event->getRequest();

        if (count($request->mapItems)) {
            /** @var MapItemSubRequest $mapItemRequest */
            foreach ($request->mapItems as $mapItemRequest) {
                $position = new Position($mapItemRequest->x, $mapItemRequest->y);

                if ($mapItemRequest->layerName) {
                    /**
                     * @var Layer $layer
                     * @var Pencil $pencil
                     */
                    $layer = $this->getContainer()->get('bluebear.manager.layer')->findOneBy([
                        'name' => $mapItemRequest->layerName
                    ]);
                    $this->throwUnless($layer, 'Layer not found');

                    // if a pencil name is provided, we update existing map item or we create it. If not, we delete
                    // existing map item
                    if ($mapItemRequest->pencilName) {
                        $pencil = $this->getContainer()->get('bluebear.manager.pencil')->findOneBy([
                            'name' => $mapItemRequest->pencilName
                        ]);
                        $this->throwUnless($pencil, 'Pencil not found');
                        // try to find an existing item
                        $mapItem = $this->getMapItemManager()->findByPositionAndLayer($position, $layer);
                        // if map item exists, we just change pencil
                        if ($mapItem) {
                            $mapItem->setPencil($pencil);
                        } else {
                            // if map item does not exists, we create it
                            $mapItem = new MapItem();
                            $mapItem->setContext($event->getContext());
                            $mapItem->setX($mapItemRequest->x);
                            $mapItem->setY($mapItemRequest->y);
                            $mapItem->setLayer($layer);
                            $mapItem->setPencil($pencil);
                        }
                        $this->getMapItemManager()->save($mapItem);
                    } else {
                        // try to find an existing item
                        $mapItem = $this->getMapItemManager()->findByPositionAndLayer($position, $layer);
                        $this->throwUnless($mapItem, 'Unable to delete. Map item not found');
                        $this->getMapItemManager()->delete($mapItem);
                    }
                }
            }
        }
    }

    /**
     * @return MapItemManager
     */
    protected function getMapItemManager()
    {
        return $this->getContainer()->get('bluebear.manager.map_item');
    }
}