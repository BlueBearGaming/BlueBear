<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            // return event
            return $engineEvent;
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "\n";
            echo $e->getTraceAsString();
            die;
            $response = $this->createResponse(self::RESPONSE_CODE_KO, $e->getMessage(), 200);
        }
        return $response;
    }
} 