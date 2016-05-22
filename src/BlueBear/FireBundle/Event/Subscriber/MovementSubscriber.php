<?php

namespace BlueBear\FireBundle\Event\Subscriber;

use BlueBear\CoreBundle\Entity\Map\MapItemRepository;
use BlueBear\CoreBundle\Path\Converter\ASCIIConverter;
use BlueBear\CoreBundle\Path\PathUtils;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\FireBundle\Game\Request\FiremanClick;
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
            'bluebear.fire.firemanClick' => 'firemanClick'
        ];
    }

    public function move(EngineEvent $event)
    {
        $response = $event->getResponse();
        $response->setData([
            'x' => 1,
            'y' => 0
        ]);
    }

    public function firemanClick(EngineEvent $engineEvent)
    {
        /** @var FiremanClick $request */
        $request = $engineEvent->getRequest();
        $map = $engineEvent
            ->getContext()
            ->getMap();

        $pathUtils = new PathUtils(new ASCIIConverter());
        $pathUtils->getNearestNeighbour(new Position(), $map);

        var_dump($request);


    }
}
