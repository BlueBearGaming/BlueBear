<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\ContextManager;
use BlueBear\CoreBundle\Manager\LayerManager;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Manager\PencilManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Engine\Context\ContextFactory;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapLoadRequest;
use BlueBear\EngineBundle\Event\Request\MapUpdateRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapUpdateItemSubRequest;
use BlueBear\EngineBundle\Event\Response\MapLoadResponse;
use BlueBear\EngineBundle\Event\Response\MapUpdateResponse;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Factory\EntityTypeFactory;
use BlueBear\EngineBundle\Manager\EntityInstanceManager;
use BlueBear\EngineBundle\Manager\EntityModelManager;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MapSubscriber
 *
 * Handles interactions between client engine and map (load and update)
 */
class MapSubscriber implements EventSubscriberInterface
{
    /** @var MapItemManager */
    protected $mapItemManager;

    /** @var ContextManager */
    protected $contextManager;

    /** @var ContextFactory */
    protected $contextFactory;

    /** @var EntityInstanceManager */
    protected $entityInstanceManager;

    /** @var EntityTypeFactory */
    protected $entityTypeFactory;

    /** @var LayerManager */
    protected $layerManager;

    /** @var PencilManager */
    protected $pencilManager;

    /** @var EntityModelManager */
    protected $entityModelManager;

    /**
     * Subscribe on mapLoad, mapSave events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_LOAD => 'onMapLoad',
            EngineEvent::EDITOR_MAP_UPDATE => 'onMapUpdate',
        ];
    }

    /**
     * Load context and return it into event response
     *
     * @param EngineEvent $event
     *
     * @throws Exception
     */
    public function onMapLoad(EngineEvent $event)
    {
        /** @var MapLoadRequest $request */
        $request = $event->getRequest();

        if ($request->loadContext) {
            $topLeft = $request->loadContext->topLeft;
            $bottomRight = $request->loadContext->bottomRight;

            if (!$topLeft || !$topLeft->x or !$topLeft->y) {
                throw new \RuntimeException('Invalid starting point (top left coordinates are not valid)');
            }
            if (!$bottomRight || !$bottomRight->x or !$bottomRight->y) {
                throw new \RuntimeException('Invalid ending point (bottom right coordinates are not valid)');
            }
            // find context with limit for map items
            $context = $this->contextManager->findWithLimit($request->contextId, $topLeft, $bottomRight);

            $mapItems = $context->getMapItems();
            /** @var MapItem $mapItem */
            foreach ($mapItems as $mapItem) {
                // find an entity instance exist at this position
                /** @var EntityInstance $entityInstance */
                $entityInstance = $this->entityInstanceManager
                    ->findOneBy(
                        [
                            'mapItem' => $mapItem->getId(),
                        ]
                    );

                if ($entityInstance) {
                    $entitiesBehaviors = $this->entityTypeFactory->getEntityBehaviors();
                    $behaviors = $entityInstance->getBehaviors();
                    // adding entity listeners for each behaviors from configuration
                    foreach ($behaviors as $behavior) {
                        if (!array_key_exists($behavior, $entitiesBehaviors)) {
                            throw new \InvalidArgumentException("Invalid behavior: {$behavior}");
                        }
                        $mapItem->addListener(
                            'click',
                            [
                                'name' => $entitiesBehaviors[$behavior]->getListener(),
                            ]
                        );
                    }
                }
            }
            if (!$context) {
                throw new Exception('Context not found');
            }
            $event->setContext($context);
        }
        $event->getContext()->setListeners(
            $this->entityTypeFactory->getEntityBehaviors()
        );
        /** @var MapLoadResponse $response */
        $response = $event->getResponse();
        // set context as event response to data
        $response->setData($event->getContext());
    }

    /**
     * Update map items
     *
     * @param EngineEvent $event
     *
     * @throws Exception
     */
    public function onMapUpdate(EngineEvent $event)
    {
        /**
         * @var MapUpdateRequest  $request
         * @var MapUpdateResponse $response
         */
        $request = $event->getRequest();
        $response = $event->getResponse();
        $context = $event->getContext();

        if (0 === count($request->mapItems)) {
            throw new \UnexpectedValueException('request.mapItems must contains mapItems');
        }

        $updated = [];
        $removed = [];
        /** @var MapUpdateItemSubRequest $mapItemRequest */
        foreach ($request->mapItems as $mapItemRequest) {
            if (!$mapItemRequest->layerName) {
                throw new \UnexpectedValueException('mapItem.layerName missing');
            }
            $position = new Position($mapItemRequest->x, $mapItemRequest->y);
            /** @var Layer $layer */
            $layer = $this->layerManager
                ->findOneBy(
                    [
                        'name' => $mapItemRequest->layerName,
                    ]
                );
            if (!$layer) {
                throw new \UnexpectedValueException('Layer not found');
            }
            // if a pencil name is provided, we update existing map item or we create it. If not, we delete
            // existing map item
            if ($mapItemRequest->pencilName) {
                /** @var Pencil $pencil */
                $pencil = $this->pencilManager->findOneBy(
                    [
                        'name' => $mapItemRequest->pencilName,
                    ]
                );
                if (!$pencil) {
                    throw new \UnexpectedValueException('Pencil not found');
                }
                // try to find an existing item
                $mapItem = $this
                    ->mapItemManager
                    ->findByPositionAndLayer($context, $position, $layer);
                // update current map item according to pencil and map item
                $updated[] = $this->updateMapItem($context, $pencil, $mapItem, $layer, $position);
            } else {
                // try to find an existing item
                $mapItem = $this->mapItemManager->findByPositionAndLayer($context, $position, $layer);
                if (!$mapItem) {
                    throw new \UnexpectedValueException('Unable to delete. Map item not found');
                }
                $this->mapItemManager->delete($mapItem);
                $removed[] = $mapItem;
            }
        }
        $response->setData($updated, $removed);

        if (count($updated)) {
            $event->setRequestClientUpdate(true);
        }
    }

    /**
     * @param Context  $context
     * @param Pencil   $pencil
     * @param MapItem  $mapItem
     * @param Layer    $layer
     * @param Position $requestPosition
     *
     * @throws Exception
     *
     * @return MapItem
     */
    protected function updateMapItem(
        Context $context,
        Pencil $pencil,
        MapItem $mapItem = null,
        Layer $layer,
        Position $requestPosition
    ) {
        // if map item exists, we just change pencil
        if ($mapItem) {
            $mapItem->setPencil($pencil);
            $this->mapItemManager->save($mapItem);
        } else {
            // searching if an entity model is linked to the pencil
            /** @var EntityModel $entityModel */
            $entityModel = $this->entityModelManager
                ->findOneBy(
                    [
                        'pencil' => $pencil->getId(),
                    ]
                );
            // if a entity model is found, we add it with its listeners. Map item will be created automatically
            if ($entityModel) {
                $this->entityInstanceManager
                    ->create(
                        $context,
                        $entityModel,
                        $requestPosition,
                        $layer
                    );
            } else {
                // if map item does not exists, we create it
                $mapItem = new MapItem();
                $mapItem->setContext($context);
                $mapItem->setX($requestPosition->x);
                $mapItem->setY($requestPosition->y);
                $mapItem->setLayer($layer);
                $mapItem->setPencil($pencil);
                $this->mapItemManager->save($mapItem);
            }
        }

        return $mapItem;
    }
}
