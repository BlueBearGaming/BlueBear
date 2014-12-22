<?php

namespace BlueBear\EngineBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\EngineBundle\Engine\Engine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $engineEvent = $engine->run($eventName, json_decode($eventData));

        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);
        $response->setData([
            'code' => $engineEvent->getResponseCode(),
            'data' => $engineEvent->getResponseData()
        ]);
        return $response;
    }
} 