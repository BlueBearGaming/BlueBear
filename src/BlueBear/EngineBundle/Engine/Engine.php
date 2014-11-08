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
            $event = new EngineEvent();
            $event->setData($eventData);
            // trigger onEngineEvent
            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $event);
            // trigger wanted event
            $this->getEventDispatcher()->dispatch($eventName, $event);
            // everything went fine, send ok response
            $response = $this->createResponse(self::RESPONSE_CODE_OK, ':)', 200);
        } catch (Exception $e) {
            $response = $this->createResponse(self::RESPONSE_CODE_KO, $e->getMessage(), 200);
        }
        return $response;
    }

    protected function createResponse($code, $data, $statusCode = 200)
    {
        $response = new JsonResponse();
        $response->setStatusCode($statusCode);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);
        $response->setData([
            'code' => $code,
            'data' => $data
        ]);
        return $response;
    }
} 