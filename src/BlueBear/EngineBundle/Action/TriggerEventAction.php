<?php

namespace BlueBear\EngineBundle\Action;

use BlueBear\EngineBundle\Engine\Engine;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Listener\KernelTerminateEventListener;
use Nc\Bundle\ElephantIOBundle\Service\Client;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Main endpoint for game api
 */
class TriggerEventAction
{
    /** @var Engine */
    protected $engine;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var KernelTerminateEventListener */
    protected $terminateListener;

    /** @var Client */
    protected $client;

    /**
     * @param Engine                       $engine
     * @param SerializerInterface          $serializer
     * @param KernelTerminateEventListener $terminateListener
     * @param Client                       $client
     */
    public function __construct(
        Engine $engine,
        SerializerInterface $serializer,
        KernelTerminateEventListener $terminateListener,
        Client $client
    ) {
        $this->engine = $engine;
        $this->serializer = $serializer;
        $this->terminateListener = $terminateListener;
        $this->client = $client;
    }

    /**
     * @param Request $request
     * @param string  $eventName
     * @param mixed   $eventData
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function __invoke(Request $request, $eventName, $eventData = null)
    {
        if (null === $eventData) {
            $eventData = $request->getContent();
        }

        $engineEvent = $this->engine->run($eventName, $eventData);
        $content = $this->serializer->serialize($engineEvent->getResponse(), 'json');

        // Register async callback
        $this->terminateListener->addCallBack(
            function () use ($content) {
                $this->client->send(EngineEvent::ENGINE_CLIENT_UPDATE, ['event' => $content]);
            }
        );

        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
