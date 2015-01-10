<?php

namespace BlueBear\EngineBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\EngineBundle\Engine\Engine;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EngineController extends Controller
{
    use ControllerBehavior;

    public function triggerEventAction(Request $request)
    {
        // api event name
        $eventName = $request->get('eventName');
        // api event data
        $eventData = $request->get('eventData');
        /** @var Engine $engine */
        $engine = $this->get('bluebear.engine.engine');
        $engineEvent = $engine->run($eventName, $eventData);

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        $content = $serializer->serialize($engineEvent->getResponse(), 'json');

        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
} 