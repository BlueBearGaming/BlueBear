<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\MapManager;
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

    /**
     * @var MapManager
     */
    protected $mapManager;

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
                throw new Exception('Invalid event data');
            }
            $event = $this->load($eventData);
            $this->getEventDispatcher()->dispatch($eventName, $event);

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

    /**
     * Create an engine event and load map into it
     *
     * @param $eventData
     * @return EngineEvent
     * @throws Exception
     */
    protected function load($eventData)
    {
        if (!$eventData->mapId) {
            throw new Exception('Invalid mapId');
        }
        /** @var Map $map */
        $map = $this->getMapManager()->find($eventData->mapId);

        if (!$map) {
            throw new Exception('Map not found');
        }
        // dispatch event to the engine
        $event = new EngineEvent();
        $event->setData($eventData);
        $event->setMap($map);
        // dispatch map load event
        $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_MAP_LOAD, $event);

        return $event;
    }

    /**
     * @return MapManager
     */
    public function getMapManager()
    {
        return $this->mapManager;
    }

    /**
     * @param MapManager $mapManager
     */
    public function setMapManager(MapManager $mapManager)
    {
        $this->mapManager = $mapManager;
    }
} 