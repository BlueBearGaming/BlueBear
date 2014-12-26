<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;

/**
 * Engine
 *
 * Dispatch an event into the game engine
 */
class Engine
{
    use HasEventDispatcher;

    const RESPONSE_CODE_OK = 'ok';
    const RESPONSE_CODE_KO = 'ko';

    /**
     * @var Map
     */
    protected $map;

    /**
     * Run map engine with an event name and event data
     *
     * @param $eventName
     * @param $eventData
     * @return EngineEvent
     */
    public function run($eventName, $eventData)
    {
        try {
            // only BlueBear events are allowed to be triggered here, not Symfony ones
            if (strpos($eventName, 'bluebear.') !== 0) {
                throw new Exception('Invalid event name. Bluebear events name should start with "bluebear."');
            }
            // check if event is allowed
            if (!in_array($eventName, EngineEvent::getAllowedEvents())) {
                throw new Exception('Invalid event name. Allowed events name are "' . implode('", "', EngineEvent::getAllowedEvents()) . '"');
            }
            if (!$eventData) {
                throw new Exception('Empty event data');
            }
            $engineEvent = new EngineEvent();
            $engineEvent->setData($eventData);
            $engineEvent->setEventName($eventName);
            // trigger onEngineEvent
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $engineEvent);
            // trigger wanted event
            $this->getEventDispatcher()->dispatch($eventName, $engineEvent);
        } catch (Exception $e) {
            $engineEvent = new EngineEvent();
            $engineEvent->setResponseCode(EngineEvent::ENGINE_EVENT_RESPONSE_KO);
            // TODO only return stack trace in debug
            $engineEvent->setResponseData([
                'message' => $e->getMessage(),
                'stackTrace' => $e->getTraceAsString()
            ]);
        }
        // return event
        return $engineEvent;
    }
} 