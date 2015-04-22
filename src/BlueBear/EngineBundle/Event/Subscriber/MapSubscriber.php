<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Behavior\HasContextFactory;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapLoadRequest;
use BlueBear\EngineBundle\Event\Request\MapUpdateRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapUpdateItemSubRequest;
use BlueBear\EngineBundle\Event\Response\MapLoadResponse;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use HasContextFactory, ContainerTrait, HasException;

    /**
     * Subscribe on mapLoad, mapSave events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_LOAD => 'onMapLoad',
            EngineEvent::EDITOR_MAP_UPDATE => 'onMapUpdate'
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
        /** @var MapLoadRequest $request */
        $request = $event->getRequest();

        if ($request->loadContext) {
            $topLeft = $request->loadContext->topLeft;
            $bottomRight = $request->loadContext->bottomRight;

            if (!$topLeft or !$topLeft->x or !$topLeft->y) {
                throw new Exception('Invalid starting point (top left coordinates are not valid)');
            }
            if (!$bottomRight or !$bottomRight->x or !$bottomRight->y) {
                throw new Exception('Invalid ending point (bottom right coordinates are not valid)');
            }
            $context = $this
                ->getContainer()
                ->get('bluebear.manager.context')
                ->findWithLimit($request->contextId, $topLeft, $bottomRight);

            if (!$context) {
                throw new Exception('Context not found');
            }
            $event->setContext($context);
        }
        $event->getContext()->setListeners($this->container->get('bluebear.game.entity_type_factory')->getEntityBehaviors());
        /** @var MapLoadResponse $response */
        $response = $event->getResponse();
        // set context as event response to data
        $response->setData($event->getContext());
    }

    /**
     * Update map items
     *
     * @param EngineEvent $event
     * @throws Exception
     */
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
            /** @var MapUpdateItemSubRequest $mapItemRequest */
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
