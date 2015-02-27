<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\EngineBundle\Behavior\HasContextFactory;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapLoadRequest;
use BlueBear\EngineBundle\Event\Response\MapLoadResponse;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MapSubscriber implements EventSubscriberInterface
{
    use HasContextFactory, ContainerTrait;

    /**
     * Subscribe on mapLoad, mapSave events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_MAP_LOAD => 'onMapLoad'
        ];
    }

    /**
     * Load context and return it into event response
     *
     * @param EngineEvent $event
     * @throws Exception
     */
    public function onMapLoad(EngineEvent $event)
    {
        /** @var MapLoadRequest $request */
        $request = $event->getRequest();

        if ($request->loadContext) {
            $topLeft = $request->loadContext->topLeft;
            $bottomRight = $request->loadContext->bottomRight;

            if (!$topLeft or !$topLeft->x or !$topLeft->y) {
                throw new Exception('Invalid starting point (top left coordinates are not valid)');
            }

            if (!$bottomRight or !$bottomRight->x or !$bottomRight->y) {
                throw new Exception('Invalid ending point (bottom right coordinates are not valid)');
            }
            $context = $this
                ->getContainer()
                ->get('bluebear.manager.context')
                ->findWithLimit($request->contextId, $topLeft, $bottomRight);

            if (!$context) {
                throw new Exception('Context not found');
            }
            $event->setContext($context);
        }
        /** @var MapLoadResponse $response */
        $response = $event->getResponse();
        // set context as event response to data
        $response->data = $event->getContext();
    }
}