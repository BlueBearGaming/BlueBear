<?php

namespace BlueBear\EngineBundle\Engine;

use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\EngineEventDefinition;
use BlueBear\EngineBundle\Event\EngineEventInterface;
use BlueBear\EngineBundle\Event\Response\ErrorResponse;
use Exception;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Engine
 *
 * Dispatch an event into the game engine
 */
class Engine
{
    const RESPONSE_CODE_OK = 200;
    const RESPONSE_CODE_KO = 500;

    /**
     * @var ParameterBag
     */
    protected $eventDefinitions;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Format for request and response serialization
     *
     * @var string
     */
    protected $serializationFormat = 'json';

    /**
     * Engine constructor. Initialize event definitions parameter bag
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface $serializer
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, SerializerInterface $serializer)
    {
        $this->eventDefinitions = new ParameterBag();
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    /**
     * Trigger game event according to event name and event request and response configuration
     *
     * @param $eventName
     * @param $eventData
     * @return EngineEvent
     */
    public function run($eventName, $eventData)
    {
        $event = null;

        try {
            // check if event is allowed
            if (!in_array($eventName, $this->eventDefinitions->keys())) {
                throw new Exception('Not allowed event ' . $eventName);
            }
            if (!is_string($eventData)) {
                throw new Exception('You should pass a json string as engine event data instead of ' . print_r($eventData, true));
            }
            // get definition according to event name
            $eventDefinition = $this
                ->eventDefinitions
                ->get($eventName);
            // create event from definition
            $event = $this->createEvent($eventDefinition, $eventData);

            // trigger onEngineEvent to enrich engine event with game required data
            $this
                ->eventDispatcher
                ->dispatch(EngineEvent::ENGINE_ON_ENGINE_EVENT, $event);
            // trigger required event
            $this
                ->eventDispatcher
                ->dispatch($eventName, $event);
        } catch (Exception $e) {
            // on error, set response code ko and return response with exception message and stack trace
            $response = new ErrorResponse($eventName);
            $response->name = $eventName;
            $response->code = EngineEvent::ENGINE_EVENT_RESPONSE_KO;
            $response->message = $e->getMessage();
            $response->stackTrace = $e->getTraceAsString();
            // create error response
            $event = new EngineEvent(null, $response);
        }
        return $event;
    }

    /**
     * Register allowed events by creating an event definition containing event configuration values
     *
     * @param array $eventsConfiguration
     */
    public function registerEvents(array $eventsConfiguration)
    {
        $resolver = new OptionsResolver();

        foreach ($eventsConfiguration as $name => $eventConfiguration) {
            // check event configuration validity
            $resolver->clear();
            $resolver->setRequired([
                'request',
                'response',
            ]);
            $resolver->setDefaults([
                'event_class' => 'BlueBear\EngineBundle\Event\EngineEvent'
            ]);
            $resolver->setAllowedTypes('request', 'string');
            $resolver->setAllowedTypes('response', 'string');
            $eventConfiguration = $resolver->resolve($eventConfiguration);

            // add definition to the bag
            $this->registerEvent($name, new EngineEventDefinition(
                $name,
                $eventConfiguration['request'],
                $eventConfiguration['response'],
                $eventConfiguration['event_class']
            ));
        }
    }

    /**
     * Register a event definition
     *
     * @param string $name Event name
     * @param EngineEventDefinition $definition
     */
    public function registerEvent($name, EngineEventDefinition $definition)
    {
        $this
            ->eventDefinitions
            ->set($name, $definition);
    }

    /**
     * Create an event with its hydrated request and response
     *
     * @param EngineEventDefinition $definition
     * @param $data
     * @return EngineEventInterface
     */
    protected function createEvent(EngineEventDefinition $definition, $data)
    {
        // create request instance hydrated with deserialize data
        $request = $this
            ->serializer
            ->deserialize($data, $definition->getRequestClass(), $this->serializationFormat);

        // create empty response; response will be hydrated by events
        $response = $this
            ->serializer
            ->deserialize([], $definition->getResponseClass(), $this->serializationFormat);

        // instanciate event
        $eventClass = $definition->getEventName();
        $event = new $eventClass($request, $response);

        return $event;
    }
}
