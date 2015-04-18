<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\EngineBundle\Behavior\HasException;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapItemClickRequest;
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
        /** @var PersistentCollection $mapItems */
        $mapItems = $event->getContext()->getMapItems();
        $source = $request->source;

        // find map item source
        $mapItemSources = $mapItems->filter(function (MapItem $mapItem) use ($source) {
            // find map item by position
            return $mapItem->getX() == $source->position->x &&
                   $mapItem->getY() == $source->position->y;
        });
        $mapItemsFound = count($mapItemSources);

        if ($mapItemsFound) {
            /** @var MapItem $mapItemSource */
            foreach ($mapItemSources as $mapItemSource) {
                // on map item click, if map item has an entity instance and this entity is movable, we should display
                // available destination locations to move on
                $entityInstance = $mapItemSource->getEntityInstance();

                if ($entityInstance && $entityInstance->has('movement')) {
                    $availableMapItemsForMovement = $this->getContainer()->get('bluebear.engine.path_finder')->findAvailable(
                        $event->getContext(),
                        $source->position,
                        $entityInstance->get('movement')
                    );
                    $event->getResponse()->data = $availableMapItemsForMovement;
                }
            }
        } else {
            throw new Exception('Map item source not found');
        }
    }
}
