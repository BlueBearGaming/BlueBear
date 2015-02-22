<?php

namespace BlueBear\EditorBundle\Event\Map;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::EDITOR_MAP_PUT_PENCIL => 'onPutPencil'
        ];
    }

    public function onPutPencil(EngineEvent $event)
    {
        /**
         * @var PutPencilRequest $request
         * @var Layer $layer
         * @var Pencil $pencil
         */
        $request = $event->getRequest();
        $layer = $this
            ->getContainer()
            ->get('bluebear.manager.layer')
            ->find($request->layerId);

        if (!$layer) {
            throw new Exception('Layer not found');
        }
        $pencil = $this
            ->getContainer()
            ->get('bluebear.manager.pencil')
            ->find($request->pencilId);

        if (!$pencil) {
            throw new Exception('Pencil not found');
        }
        $mapItem = new MapItem();
        $mapItem->setContext($event->getContext());
        $mapItem->setLayer($layer);
        $mapItem->setPencil($pencil);
        $mapItem->setX($request->x);
        $mapItem->setY($request->y);
        // saving new map item
        $this
            ->getContainer()
            ->get('bluebear.manager.map_item')
            ->save($mapItem);
    }
}