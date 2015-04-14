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

        $mapItemSources = $mapItems->filter(function (MapItem $mapItem) use ($source) {
            // find map item by position
            return $mapItem->getX() == $source->position->x &&
                   $mapItem->getY() == $source->position->y;
        });

        if (count($mapItemSources) == 1) {
            /** @var MapItem $mapItemSource */
            $mapItemSource = $mapItemSources->first();
        }
    }
}
