<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapItemClickRequest;
use BlueBear\EngineBundle\Event\Response\MapItemClickResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MapItemSubscriber
 *
 * Handle map item interaction (click, move)
 */
class MapItemSubscriber implements EventSubscriberInterface
{
    use ContainerTrait, HasException;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_ITEM_CLICK => [
                'onClick'
            ],
            EngineEvent::ENGINE_MAP_ITEM_MOVE => [
                'onMove'
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
        /** @var MapItemClickRequest $request */
        $request = $event->getRequest();
        /** @var MapItemClickResponse $response */
        $response = $event->getResponse();
        /** @var PersistentCollection $mapItems */
        $mapItems = $event->getContext()->getMapItems();
        $target = $request->target;
        // target should be defined
        if (!$target) {
            throw new Exception('Target should be defined for map click event');
        }
        // find map item target
        $mapItemTargets = $mapItems->filter(function (MapItem $mapItem) use ($target) {
            // find map item by position
            return $mapItem->getX() == $target->position->x
            && $mapItem->getY() == $target->position->y
            && $mapItem->getLayer()->getType() == $target->layer;
        });
        $mapItemsFoundCount = count($mapItemTargets);

        if ($mapItemsFoundCount == 1) {
            $availableMapItemsForMovement = [];
            /** @var MapItem $mapItemTarget */
            $mapItemTarget = $mapItemTargets->first();
            // on map item click, if map item has an entity instance and this entity is movable, we should display
            // available destination locations to move on
            $entityInstance = $this->getContainer()->get('bluebear.manager.entity_instance')->findOneBy([
                'mapItem' => $mapItemTarget->getId()
            ]);
            if ($entityInstance && $entityInstance->has('movement')) {
                $availableMapItemsForMovement = $this->getContainer()->get('bluebear.engine.path_finder')->findAvailable(
                    $event->getContext(),
                    $target->position,
                    $entityInstance->get('movement')
                );
            }
            $this->throwUnless($entityInstance->hasBehavior('selectable'), 'Entity has no selectable behavior');
            $mapItems = $this->getMapItemForSelection($availableMapItemsForMovement, $entityInstance->getMapItem());
            // we return available map item for movement for response
            $response->setData($mapItems);
        } else if ($mapItemsFoundCount > 1) {
            // engine rule: there should be only one map item by position and by layer
            throw new Exception('Too many map item found');
        } else {
            throw new Exception('Map item target not found on this layer');
        }
    }

    /**
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onMove(EngineEvent $event)
    {
        /** @var MapItemClickRequest $request */
        $request = $event->getRequest();
        /** @var MapItemClickResponse $response */
        $response = $event->getResponse();
        /** @var PersistentCollection $mapItems */
        $mapItems = $event->getContext()->getMapItems();
        $target = $request->target;
        $source = $request->source;
        // target should be defined
        $this->throwUnless($source, 'Source should be defined for map click event');
        $this->throwUnless($target, 'Target should be defined for map click event');
        // find map item source
        $mapItemSource = $this->findOneMapItem($mapItems, $source->position, $source->layer);
        // find map item target
        $mapItemTarget = $this->findOneMapItem($mapItems, $target->position, $target->layer);
        $entityInstance = $this->getContainer()->get('bluebear.manager.entity_instance')->findOneBy([
            'mapItem' => $mapItemSource->getId()
        ]);
        // on map item move, we check if the entity instance can move on selected map item target
        if ($entityInstance && $entityInstance->has('movement')) {
            $pathFinder = $this
                ->getContainer()
                ->get('bluebear.engine.path_finder');
            /** @var ArrayCollection $availableMapItemsForMovement */
            $availableMapItemsForMovement = $pathFinder->findAvailable(
                $event->getContext(),
                $mapItemSource->getPosition(),
                $entityInstance->get('movement')
            );
            // TODO add ruler to check if unit is allowed to move on target map item according to the game rule
            // targeted map item should be available for movement, because it should coming from a previous call
            // to MapItemClick method.
            $exists = $availableMapItemsForMovement->filter(function (MapItem $mapItem) use ($mapItemTarget) {
                return $mapItem->getId() == $mapItemTarget->getId();
            });
            $this->throwUnless($exists, 'MapItem target not available for movement');
            $manager = $this->getMapItemManager();
            // moving map item to target location
            $entityInstance->getMapItem()->setPosition($mapItemTarget->getPosition());
            $manager->save($entityInstance);
            $manager->save($entityInstance->getMapItem());
            // we return current map item source filled with the path to the target
            $path = $pathFinder->findPath($mapItems, $source->position, $target->position);
            $mapItemSource->setPath($path);
            $mapItemSource->setPosition($source->position);
            $clickListener = [
                'name' => EngineEvent::ENGINE_MAP_ITEM_CLICK
            ];
            $mapItemSource->addListener('click', $clickListener);

            $response->setData([
                $mapItemSource
            ]);
        }
    }

    /**
     * Return map items on selection layer from available map item for entity movement
     *
     * @param MapItem[] $mapItems
     * @param MapItem $source
     * @return ArrayCollection
     */
    protected function getMapItemForSelection($mapItems, MapItem $source)
    {
        $mapItemsForSelection = new ArrayCollection();
        // selection layer is a "fake" layer. It is not store in database, and it is created on demand
        $selectionLayer = new Layer();
        $selectionLayer->setName(Constant::LAYER_TYPE_SELECTION);
        // selection pencil should be handled client side
        $selectionPencil = new Pencil();
        $selectionPencil->setName(Constant::PENCIL_TYPE_SELECTION);
        // event layer
        $eventLayer = new Layer();
        $emptyPencil = new Pencil();
        $emptyPencil->setName(Constant::PENCIL_TYPE_EMPTY);
        $eventLayer->setName(Constant::LAYER_TYPE_EVENTS);
        $clickListener = [
            'name' => EngineEvent::ENGINE_MAP_ITEM_MOVE,
            'source' => []
        ];
        // foreach available map item for movement, we create one map item on the selection layer and one map item
        // on the event layer (on top of layers stack)
        foreach ($mapItems as $mapItem) {
            // source target for next click event should be current map item
            $clickListener['source']['position'] = new Position($source->getX(), $source->getY());
            $clickListener['source']['layer'] = Constant::LAYER_TYPE_UNIT;
            // map item for selection layer
            $mapItemForSelection = new MapItem();
            $mapItemForSelection->setLayer($selectionLayer);
            $mapItemForSelection->setPencil($selectionPencil);
            $mapItemForSelection->setX($mapItem->getX());
            $mapItemForSelection->setY($mapItem->getY());
            // map item for event layer
            $mapItemForEvent = new MapItem();
            $mapItemForEvent->setX($mapItem->getX());
            $mapItemForEvent->setY($mapItem->getY());
            $mapItemForEvent->setListeners([
                'click' => $clickListener
            ]);
            $mapItemForEvent->setLayer($eventLayer);
            $mapItemForEvent->setPencil($emptyPencil);
            // map item for event layer
            // add created map item to the collection
            $mapItemsForSelection->add($mapItemForSelection);
            $mapItemsForSelection->add($mapItemForEvent);
        }
        return $mapItemsForSelection;
    }

    /**
     * @param PersistentCollection $mapItems
     * @param Position $position
     * @param $layerType
     * @return MapItem
     */
    protected function findOneMapItem($mapItems, Position $position, $layerType)
    {
        $virtualLayers = Constant::getVirtualLayers();

        // find map item target
        $mapItemsFound = $mapItems->filter(function (MapItem $mapItem) use ($position, $layerType, $virtualLayers) {
            // find map item by position
            $positionFound = $mapItem->getX() == $position->x && $mapItem->getY() == $position->y;
            // if layer is virtual (not stored in database), we do not check if map item exist on it
            if (!in_array($layerType, $virtualLayers)) {
                $positionFound = $positionFound && ($mapItem->getLayer()->getType() == $layerType);
            }
            return $positionFound;
        });
        $count = count($mapItemsFound);
        $this->throwUnless($count > 0,
            'Map item not found on this layer "' . $layerType . '", position : x:' . $position->x . ', y:' . $position->y);

        if (!in_array($layerType, $virtualLayers)) {
            $this->throwUnless($count == 1, 'Too many map item found');
        }
        return $mapItemsFound->first();
    }

    /**
     * @return MapItemManager
     */
    protected function getMapItemManager()
    {
        return $this->getContainer()->get('bluebear.manager.map_item');
    }
}
