<?php

namespace BlueBear\EngineBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\EngineBundle\Engine\Engine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EngineController extends Controller
{
    use ControllerBehavior;

    public function triggerEventAction(Request $request)
    {
        $eventName = $request->get('eventName');
        $eventData = $request->get('eventData');
        /** @var Engine $engine */
        $engine = $this->get('bluebear.engine.engine');
        $response = $engine->run($eventName, json_decode($eventData));

        return $response;
    }
} 