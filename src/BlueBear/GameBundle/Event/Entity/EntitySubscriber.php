<?php

namespace BlueBear\GameBundle\Event\Entity;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\GameBundle\Entity\Unit;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * EntitySubscriber
 *
 * Subscribe event for entity management
 */
class EntitySubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_MAP_PUT_ENTITY => [
                'putEntity'
            ]
        ];
    }

    /**
     * Create a instance of an entity in a map at given position
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function putEntity(EngineEvent $event)
    {
        /** @var PutEntityRequest $request */
        $request = $event->getRequest();
        /** @var Entity $entity */
        $entity = $this->getContainer()->get('bluebear.manager.entity')->find($request->entityId);

        if (!$entity) {
            throw new Exception('Unable to create entity instance in map. Invalid entity id');
        }
        if (!$request->x or !$request->y) {
            throw new Exception('Unable to create entity instance in map. Invalid coordinates');
        }
        $position = new Position($request->x, $request->y);
        // create an instance of the entity into the map
        $this->getContainer()->get('bluebear.game.entity_factory')->create($event->getContext(), $entity, $position);
        // set event response
        $response = new PutEntityResponse();
        $event->setResponse($response);
    }
}