<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Behavior\HasSerializer;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\Response\ErrorResponse;
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
        try {
            // TODO add allowed namespaces in conf
//            // only BlueBear events are allowed to be triggered here, not Symfony ones
//            if (strpos($eventName, 'bluebear.') !== 0) {
//                throw new Exception('Invalid event name. Bluebear events name should start with "bluebear."');
//            }
            // check if event is allowed
            if (!in_array($eventName, $this->getAllowedEvents())) {
                throw new Exception('Invalid event name ' . $eventName . '. Allowed events name are "' .
                    implode('", "', $this->getAllowedEvents()) . '"');
            }
            if (!is_string($eventData)) {
                throw new Exception('You should pass a json string as engine event data');
            }
            // deserialize event request
            $eventRequest = $this->getRequestForEvent($eventName, $eventData);
            $eventResponse = $this->getResponseForEvent($eventName);
            // TODO handle dynamic class
            $eventClass = $this->getClassForEvent($eventName);
            /** @var EngineEvent $engineEvent */
            $engineEvent = new $eventClass($eventRequest, $eventResponse);
            $engineEvent->setOriginEventName($eventName);
            // trigger onEngineEvent
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $engineEvent);
            // trigger required event
            $this->getEventDispatcher()->dispatch($eventName, $engineEvent);
        } catch (Exception $e) {
            if (!isset($eventRequest)) {
                $eventRequest = new EventRequest();
            }
            // on error, set response code ko and return response with exception message and stack trace
            $response = new ErrorResponse($eventName);
            $response->name = $eventName;
            $response->code = EngineEvent::ENGINE_EVENT_RESPONSE_KO;
            $response->message = $e->getMessage();
            $response->eventRequest = $eventRequest;
            $response->stackTrace = $e->getTraceAsString();
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

        if (!class_exists($requestClass)) {
            throw new Exception("Invalid request class \"{$requestClass}\" (not found). Check your configuration");
        }
        if (!is_subclass_of($requestClass, 'BlueBear\EngineBundle\Event\EventRequest')) {
            throw new Exception("{$requestClass} should extend BlueBear\\EngineBundle\\Event\\EventRequest");
        }
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
            throw new Exception("Event response class {$responseClass} not found");
        }
        if (!is_subclass_of($responseClass, 'BlueBear\EngineBundle\Event\EventResponse')) {
            throw new Exception("{$responseClass} should extend BlueBear\\EngineBundle\\Event\\EventResponse");
        }
        // create new EventResponse object
        return new $responseClass($eventName);
    }

    protected function getClassForEvent($eventName)
    {
        if (!array_key_exists($eventName, $this->allowedEvents)) {
            throw new Exception("Not allowed event name \"{$eventName}\"");
        }
        if (array_key_exists('event_class', $this->allowedEvents[$eventName])) {
            $eventClass = $this->allowedEvents[$eventName]['event_class'];

            if (!is_subclass_of($eventClass, 'BlueBear\EngineBundle\Event\EngineEvent')) {
                throw new Exception("{$eventClass} should extend 'BlueBear\\EngineBundle\\Event\\EngineEvent'");
            }
        } else {
            $eventClass = 'BlueBear\EngineBundle\Event\EngineEvent';
        }
        return $eventClass;
    }

    /**
     * @param array $eventsConfig
     * @throws Exception
     */
    public function setAllowedEvents(array $eventsConfig)
    {
        foreach ($eventsConfig as $eventName => $eventConfig) {
            if (!array_key_exists('request', $eventConfig)) {
                $eventConfig['request'] = 'BlueBear\EngineBundle\Event\Request\MapItemClickRequest';
            }
            if (!array_key_exists('response', $eventConfig)) {
                $eventConfig['response'] = 'BlueBear\EngineBundle\Event\Response\MapUpdateResponse';
            }
            // Check classes ?
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
