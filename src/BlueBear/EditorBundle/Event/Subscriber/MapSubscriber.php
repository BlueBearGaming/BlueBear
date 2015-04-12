<?php

namespace BlueBear\EditorBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EditorBundle\Event\Map\MapItemSubRequest;
use BlueBear\EditorBundle\Event\Request\MapUpdateRequest;
use BlueBear\EditorBundle\Event\Response\MapUpdateResponse;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use ContainerTrait, HasException;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::EDITOR_MAP_UPDATE => 'onMapUpdate'
        ];
    }

    public function onMapUpdate(EngineEvent $event)
    {
        /**
         * @var MapUpdateRequest $request
         * @var MapUpdateResponse $response
         */
        $request = $event->getRequest();
        $response = $event->getResponse();
        $context = $event->getContext();

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
                        $mapItem = $this->getMapItemManager()->findByPositionAndLayer($context, $position, $layer);
                        // if map item exists, we just change pencil
                        if ($mapItem) {
                            $mapItem->setPencil($pencil);
                            $response->updated[] = $mapItem;
                        } else {
                            // if map item does not exists, we create it
                            $mapItem = new MapItem();
                            $mapItem->setContext($event->getContext());
                            $mapItem->setX($mapItemRequest->x);
                            $mapItem->setY($mapItemRequest->y);
                            $mapItem->setLayer($layer);
                            $mapItem->setPencil($pencil);
                            $response->updated[] = $mapItem;
                        }
                        $this->getMapItemManager()->save($mapItem);
                    } else {
                        // try to find an existing item
                        $mapItem = $this->getMapItemManager()->findByPositionAndLayer($context, $position, $layer);
                        $this->throwUnless($mapItem, 'Unable to delete. Map item not found');
                        $this->getMapItemManager()->delete($mapItem);
                        $response->removed[] = $mapItem;
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
