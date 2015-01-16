<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Behavior\HasSerializer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Error\ErrorResponse;
use Exception;

/**
 * Engine
 *
 * Dispatch an event into the game engine
 */
class Engine
{
    use HasEventDispatcher, HasSerializer;

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
                $allowedEvents = implode('", "', EngineEvent::getAllowedEvents());
                throw new Exception('Invalid event name. Allowed events name are "' . $allowedEvents . '"');
            }
            if (!$eventData) {
                throw new Exception('Empty event data');
            }
            // deserialize event request
            $request = $this
                ->getSerializer()
                ->deserialize($eventData, $this->getRequestClassForEvent($eventName), 'json');
            $engineEvent = new EngineEvent();
            $engineEvent->setRequest($request);
            // trigger onEngineEvent
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $engineEvent);
            // trigger wanted event
            $this->getEventDispatcher()->dispatch($eventName, $engineEvent);
        } catch (Exception $e) {
            // on error, set response code ko and return response with exception message and stack trace
            $response = new ErrorResponse();
            $response->type = get_class($response);
            $response->code = EngineEvent::ENGINE_EVENT_RESPONSE_KO;
            $response->message = $e->getMessage();
            $response->stackTrace = $e->getTraceAsString();
            // set event response
            $engineEvent = new EngineEvent();
            $engineEvent->setResponse($response);
        }
        // return event
        return $engineEvent;
    }

    protected function getRequestClassForEvent($eventName)
    {
        $request = 'BlueBear\EngineBundle\Event\EventRequest';

        if ($eventName == EngineEvent::ENGINE_ON_CONTEXT_LOAD) {
            $request = 'BlueBear\EngineBundle\Event\Map\LoadContextRequest';
        } else if ($eventName == EngineEvent::ENGINE_ON_MAP_ITEM_CLICK) {
            $request = 'BlueBear\EngineBundle\Event\MapItem\MapItemClickRequest';
        }
        if (!$request) {
            throw new Exception('Event request class not found for event : ' . $eventName);
        }
        return $request;
    }
} 