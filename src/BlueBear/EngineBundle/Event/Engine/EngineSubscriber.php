<?php

namespace BlueBear\EngineBundle\Event\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Manager\ContextManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EngineSubscriber implements EventSubscriberInterface
{
    use HasEventDispatcher;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * Subscribes on engine event
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_ENGINE_EVENT => 'onEngineEvent'
        ];
    }

    /**
     * On each engine event, we should load the map
     *
     * @param EngineEvent $event
     * @return EngineEvent
     * @throws Exception
     */
    public function onEngineEvent(EngineEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->contextId) {
            throw new Exception('Invalid context id');
        }
        /** @var Context $context */
        $context = $this->getContextManager()->find($request->contextId);

        if (!$context or !count($context->getUserContexts())) {
            throw new Exception('Context not found or invalid context map');
        }
        $event->setContext($context);
        // we load map only if event is not map load to avoid calling subscribers twice
        if ($event->getName() != EngineEvent::ENGINE_ON_CONTEXT_LOAD) {
            // dispatch map load event
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_CONTEXT_LOAD, $event);
        }
        return $event;
    }

    /**
     * @return ContextManager
     */
    public function getContextManager()
    {
        return $this->contextManager;
    }

    /**
     * @param ContextManager $contextManager
     */
    public function setContextManager($contextManager)
    {
        $this->contextManager = $contextManager;
    }
}