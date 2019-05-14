<?php

namespace App\Engine;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Handler\ModelHandlerInterface;
use App\Contracts\Model\ModelInterface;
use App\Engine\Exception\EngineException;
use App\Engine\Request\EngineRequest;
use App\Engine\Response\EngineResponse;
use App\Event\Engine\EngineEvent;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class Engine implements EngineInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;
    /**
     * @var ModelHandlerInterface
     */
    private $modelHandler;

    public function __construct(
        SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher,
        ModelFactoryInterface $modelFactory,
        ModelHandlerInterface $modelHandler
    ) {
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->modelFactory = $modelFactory;
        $this->modelHandler = $modelHandler;
    }

    public function run(Request $request): Response
    {
        $engineRequest = $this->createEngineRequest($request);

        $model = $this->modelFactory->create($engineRequest->getModelName(), $engineRequest->getData());

        $event = new EngineEvent($model);

        $this->eventDispatcher->dispatch(Events::PRE_MODEL_HANDLE, $event);
        $this->modelHandler->handle($model);
        $this->eventDispatcher->dispatch(Events::POST_MODEL_HANDLE, $event);

        return $this->createEngineResponse($model);
    }

    private function createEngineRequest(Request $request): EngineRequest
    {
        if ('' === $request->getContent()) {
            throw new EngineException('Invalid data provided', 400);
        }
        $engineRequest = $this->serializer->deserialize($request->getContent(), EngineRequest::class, 'json');

        if (!$engineRequest instanceof EngineRequest) {
            throw new EngineException();
        }

        return $engineRequest;
    }

    private function createEngineResponse(ModelInterface $model): EngineResponse
    {
        $data = $this->serializer->serialize($model, 'json');

        return new EngineResponse($data, 200, [], true);
    }


//    const RESPONSE_CODE_OK = 'ok';
//
//    const RESPONSE_CODE_KO = 'ko';
//
//
//
//    protected $allowedEvents = [];
//
//    /**
//     * Run map engine with an event name and event data
//     *
//     * @param $eventName
//     * @param $eventData
//     * @return EngineEvent
//     */
//    public function runOLD($eventName, $eventData)
//    {
//        try {
//            // check if event is allowed
//            if (!in_array($eventName, $this->getAllowedEvents())) {
//                throw new Exception('Invalid event name. Allowed events name are "' .
//                    implode('", "', $this->getAllowedEvents()) . '"');
//            }
//            if (!$eventData) {
//                throw new Exception('Empty event data');
//            }
//            // deserialize event request
//            $eventRequest = $this->getRequestForEvent($eventName, $eventData);
//            $eventResponse = $this->getResponseForEvent($eventName);
//            $engineEvent = new EngineEvent($eventRequest, $eventResponse);
//            $engineEvent->setOriginEventName($eventName);
//            // trigger onEngineEvent
//            $this->getEventDispatcher()->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $engineEvent);
//            // trigger required event
//            $this->getEventDispatcher()->dispatch($eventName, $engineEvent);
//        } catch (Exception $e) {
//            if (!isset($eventRequest)) {
//                $eventRequest = new EventRequest();
//            }
//            // on error, set response code ko and return response with exception message and stack trace
//            $response = new ErrorResponse($eventName);
//            $response->name = $eventName;
//            $response->code = EngineEvent::ENGINE_EVENT_RESPONSE_KO;
//            $response->message = $e->getMessage();
//            $response->eventRequest = $eventRequest;
//            $response->stackTrace = $e->getTraceAsString();
//            // set event response
//            $engineEvent = new EngineEvent($eventRequest, $response);
//        }
//        // return event
//        return $engineEvent;
//    }
//
//    protected function getRequestForEvent($eventName, $eventData)
//    {
//        if (!array_key_exists($eventName, $this->allowedEvents)) {
//            throw new Exception("Not allowed event name \"{$eventName}\"");
//        }
//        $requestClass = $this->allowedEvents[$eventName]['request'];
//
//        if (!class_exists($requestClass)) {
//            throw new Exception("Invalid request class \"{$requestClass}\" (not found). Check your configuration");
//        }
//        if (!is_subclass_of($requestClass, 'BlueBear\EngineBundle\Event\EventRequest')) {
//            throw new Exception("{$requestClass} should extend BlueBear\\EngineBundle\\Event\\EventRequest");
//        }
//        // deserialize json data into EventRequest object
//        return $this
//            ->getSerializer()
//            ->deserialize($eventData, $requestClass, 'json');
//    }
//
//    protected function getResponseForEvent($eventName)
//    {
//        if (!array_key_exists($eventName, $this->allowedEvents)) {
//            throw new Exception("Not allowed event name \"{$eventName}\"");
//        }
//        $responseClass = $this->allowedEvents[$eventName]['response'];
//
//        if (!class_exists($responseClass)) {
//            throw new Exception("Event response class {$responseClass} not found");
//        }
//        if (!is_subclass_of($responseClass, 'BlueBear\EngineBundle\Event\EventResponse')) {
//            throw new Exception("{$responseClass} should extend BlueBear\\EngineBundle\\Event\\EventResponse");
//        }
//        // create new EventResponse object
//        return new $responseClass($eventName);
//    }
//
//    /**
//     * @param array $eventsConfig
//     * @throws Exception
//     */
//    public function setAllowedEvents(array $eventsConfig)
//    {
//        foreach ($eventsConfig as $eventName => $eventConfig) {
//            if (!array_key_exists('request', $eventConfig)) {
//                $eventConfig['request'] = 'BlueBear\EngineBundle\Event\Request\MapItemClickRequest';
//            }
//            if (!array_key_exists('response', $eventConfig)) {
//                $eventConfig['response'] = 'BlueBear\EngineBundle\Event\Response\MapUpdateResponse';
//            }
//            // Check classes ?
//            $this->allowedEvents[$eventName] = $eventConfig;
//        }
//    }
//
//    /**
//     * Return allowed event names
//     *
//     * @return array
//     */
//    public function getAllowedEvents()
//    {
//        return array_keys($this->allowedEvents);
//    }
} 
