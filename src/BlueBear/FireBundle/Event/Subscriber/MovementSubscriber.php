<?php

namespace BlueBear\FireBundle\Event\Subscriber;

use BlueBear\CoreBundle\Path\PathUtils;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\FireBundle\Game\Request\FiremanClick;
use BlueBear\FireBundle\Game\Request\FiremanMove;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovementSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'bluebear.fire.move' => 'move',
            'bluebear.fire.click' => 'click'
        ];
    }

    public function move(EngineEvent $event)
    {
        /** @var FiremanMove $request */
        $request = $event->getRequest();

        $response = $event->getResponse();
        $map = $event
            ->getContext()
            ->getMap();
        $target = $event
            ->getContext()
            ->getMapItems()
            ->get($request->destinationMapItemId);

        $source = $event
            ->getContext()
            ->getMapItems()
            ->get($request->sourceMapItemId);
        

        if ($target === null) {
            throw new LogicException('Map item ' . $request->destinationMapItemId . ' not found in current context');
        }

        $pathUtils = new PathUtils();
        $mapItems = $pathUtils->getNearestNeighbour($source->getPosition(), $map);

        foreach ($mapItems as $mapItem) {

            if ($mapItem->getId() == $target->getId()) {
                $response->setData($mapItem->getPosition());
            }
        }
    }

    public function click(EngineEvent $event)
    {
        /** @var FiremanClick $request */
        $request = $event->getRequest();
        $response = $event->getResponse();
        $map = $event
            ->getContext()
            ->getMap();
        $source = $event
            ->getContext()
            ->getMapItems()
            ->get($request->sourceMapItemId);

        $pathUtils = new PathUtils();
        $mapItems = $pathUtils->getNearestNeighbour($source->getPosition(), $map);

        $positions = [];

        foreach ($mapItems as $mapItem) {
            $positions[] = $mapItem->getPosition();
        }

        $response->setData($positions);
    }
}
