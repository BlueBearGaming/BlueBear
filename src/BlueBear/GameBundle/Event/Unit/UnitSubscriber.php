<?php

namespace BlueBear\GameBundle\Event\Unit;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\GameBundle\Entity\Unit;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * UnitSubscriber
 *
 * Subscribe event for unit management
 */
class UnitSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_PUT_UNIT => [
                'putUnit'
            ]
        ];
    }

    /**
     * Create a instance of an unit in a map at given position
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function putUnit(EngineEvent $event)
    {
        /** @var PutUnitRequest $request */
        $request = $event->getRequest();
        /** @var Unit $unit */
        $unit = $this->getContainer()->get('bluebear.manager.unit')->find($request->unitId);

        if (!$unit) {
            throw new Exception('Unable to create unit instance in map. Invalid unit id');
        }
        if (!$request->x or !$request->y) {
            throw new Exception('Unable to create unit instance in map. Invalid coordinates');
        }
        $position = new Position($request->x, $request->y);
        // create an instance of the unit into the map
        $this->getContainer()->get('bluebear.game.unit_factory')->create($event->getContext(), $unit, $position);
        // set event response
        $response = new PutUnitResponse();
        $event->setResponse($response);
    }
}