<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapItemClickRequest;
use BlueBear\EngineBundle\Event\Response\MapItemClickResponse;
use Doctrine\ORM\PersistentCollection;
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
        /** @var MapItemClickRequest $request */
        $request = $event->getRequest();
        /** @var MapItemClickResponse $response */
        $response = $event->getResponse();
        /** @var PersistentCollection $mapItems */
        $mapItems = $event->getContext()->getMapItems();
        $target = $request->target;
        $source = $request->source;
        // target should be defined
        if (!$target) {
            throw new Exception('Target should be defined for map click event');
        }
        // find map item target
        $mapItemTargets = $mapItems->filter(function (MapItem $mapItem) use ($target) {
            // find map item by position
            return $mapItem->getX() == $target->position->x
            && $mapItem->getY() == $target->position->y;
        });
        $mapItemsFound = count($mapItemTargets);

        if ($mapItemsFound) {
            $availableMapItemsForMovement = [];
            /** @var MapItem $mapItemSource */
            foreach ($mapItemTargets as $mapItemSource) {
                // on map item click, if map item has an entity instance and this entity is movable, we should display
                // available destination locations to move on
                $entityInstance = $mapItemSource->getEntityInstance();

                if ($entityInstance && $entityInstance->has('movement')) {
                    $availableMapItemsForMovement = $this->getContainer()->get('bluebear.engine.path_finder')->findAvailable(
                        $event->getContext(),
                        $target->position,
                        $entityInstance->get('movement')
                    );
                }
            }
            // if a target is provided, it is a entity movement
            if ($source) {
                // we try to find the targeted item amongst map items
                $mapItemTargets = $mapItems->filter(function (MapItem $mapItem) use ($target) {
                    return $mapItem->getX() == $target->position->x
                    && $mapItem->getY() == $target->position->y
                    && $mapItem->getLayer()->getType() == $target->layer;
                });
            } else {
                $mapItems = $this->getMapItemForSelection($availableMapItemsForMovement);
                // if no target is provided, we return available map item for movement for response
                $response->setData($mapItems);
            }
        } else {
            throw new Exception('Map item target not found');
        }
    }

    /**
     * @param MapItem[] $mapItems
     * @return array
     */
    protected function getMapItemForSelection(array $mapItems)
    {
        $mapItemsForSelection = [];
        // selection layer is a "fake" layer. It is not store in database, and it is created on demand
        $selectionLayer = new Layer();
        $selectionLayer->setName(Constant::LAYER_TYPE_SELECTION);
        // selection pencil should be handled client side
        $selectionPencil = new Pencil();
        $selectionPencil->setName(Constant::PENCIL_TYPE_SELECTION);

        foreach ($mapItems as $mapItem) {
            $mapItemForSelection = new MapItem();
            $mapItemForSelection->setLayer($selectionLayer);
            $mapItemForSelection->setPencil($selectionPencil);
            $mapItemForSelection->setListeners($mapItem->getListeners());
            $mapItemForSelection->setX($mapItem->getX());
            $mapItemForSelection->setY($mapItem->getY());
            // add created map item to the collection
            $mapItemsForSelection[] = $mapItemForSelection;
        }
        return $mapItemsForSelection;
    }
}
