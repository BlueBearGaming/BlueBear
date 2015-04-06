<?php

namespace BlueBear\GameBundle\Event\Entity;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\GameBundle\Entity\EntityModel;
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
            EngineEvent::EDITOR_MAP_PUT_ENTITY => [
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
        /** @var EntityModel $entityModel */
        $entityModel = $this->getContainer()->get('bluebear.manager.entity_model')->find($request->entityModelId);
        /** @var Layer $requestedLayer */
        $requestedLayer = $this->getContainer()->get('bluebear.manager.layer')->findOneByName($request->layerName);

        if (!$entityModel) {
            throw new Exception('Unable to create entity instance in map. Invalid entity id');
        }
        if (!$requestedLayer) {
            throw new Exception('Unable to create entity instance in map. Invalid layer id');
        }
        if (!$request->x or !$request->y) {
            throw new Exception('Unable to create entity instance in map. Invalid coordinates');
        }
        $position = new Position($request->x, $request->y);
        // create an instance of the entity into the map
        $this
            ->getContainer()
            ->get('bluebear.manager.entity_instance')
            ->create($event->getContext(), $entityModel, $position, $requestedLayer);
    }
}