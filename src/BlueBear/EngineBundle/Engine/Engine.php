<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Behavior\HasSerializer;
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

    protected $allowedEvents = [];

    /**
     * Run map engine with an event name and event data
     *
     * @param $eventName
     * @param $eventData
     * @return EngineEvent
     */
    public function run($eventName, $eventData)
    {
        $eventRequest = null;

        try {
            // only BlueBear events are allowed to be triggered here, not Symfony ones
            if (strpos($eventName, 'bluebear.') !== 0) {
                throw new Exception('Invalid event name. Bluebear events name should start with "bluebear."');
            }
            // check if event is allowed
            if (!in_array($eventName, $this->getAllowedEvents())) {
                $allowedEvents = implode('", "', $this->getAllowedEvents());
                throw new Exception('Invalid event name. Allowed events name are "' . $allowedEvents . '"');
            }
            if (!$eventData) {
                throw new Exception('Empty event data');
            }
            // deserialize event request
            $eventRequest = $this->getRequestForEvent($eventName, $eventData);
            $eventResponse = $this->getResponseForEvent($eventName);
            $engineEvent = new EngineEvent($eventRequest, $eventResponse);
            // trigger onEngineEvent
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $engineEvent);
            // trigger wanted event
            $this->getEventDispatcher()->dispatch($eventName, $engineEvent);
        } catch (Exception $e) {
            // on error, set response code ko and return response with exception message and stack trace
            $response = new ErrorResponse($eventName);
            $response->name = $eventName;
            $response->code = EngineEvent::ENGINE_EVENT_RESPONSE_KO;
            $response->message = $e->getMessage();
            // set event response
            $engineEvent = new EngineEvent($eventRequest, $response);
        }
        // return event
        return $engineEvent;
    }

    protected function getRequestForEvent($eventName, $eventData)
    {
        if (!array_key_exists($eventName, $this->allowedEvents)) {
            throw new Exception("Not allowed event name \"{$eventName}\"");
        }
        $requestClass = $this->allowedEvents[$eventName]['request'];
        // deserialize json data into EventRequest object
        return $this
            ->getSerializer()
            ->deserialize($eventData, $requestClass, 'json');
    }

    protected function getResponseForEvent($eventName)
    {
        if (!array_key_exists($eventName, $this->allowedEvents)) {
            throw new Exception("Not allowed event name \"{$eventName}\"");
        }
        $responseClass = $this->allowedEvents[$eventName]['response'];

        if (!class_exists($responseClass)) {
            throw new Exception('Invalid event response class');
        }
        // create new EventResponse object
        return new $responseClass($eventName);
    }

    /**
     * @param array $eventsConfig
     * @throws Exception
     */
    public function setAllowedEvents(array $eventsConfig)
    {
        foreach ($eventsConfig as $eventName => $eventConfig) {
            if (!array_key_exists('request', $eventConfig) or
                !array_key_exists('response', $eventConfig)) {
                throw new Exception('Event configuration should have a request and response');
            }
            $this->allowedEvents[$eventName] = $eventConfig;
        }
    }

    /**
     * Return allowed event names
     *
     * @return array
     */
    public function getAllowedEvents()
    {
        return array_keys($this->allowedEvents);
    }
} 