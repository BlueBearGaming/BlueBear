<?php

namespace BlueBear\EngineBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EngineController extends Controller
{
    use ControllerBehavior;

    public function triggerEventAction(Request $request)
    {
        $eventName = $request->get('eventName');
        $eventData = $request->get('eventData');

        // only BlueBear events are allowed to be triggered here, not Symfony ones
        if (strpos($eventName, 'bluebear.') !== 0) {
            throw new Exception('Invalid event name. Bluebear events name should start with "bluebear."');
        }
        // check if event is registered
        if (!in_array($eventName, EngineEvent::getAllowedEvents())) {
            throw new Exception('Invalid event name. Allowed events name are "' . implode('", "', EngineEvent::getAllowedEvents()) . '"');
        }
        // dispatch event to the engine
        $event = new EngineEvent();
        $event->setData($eventData);
        $this->getEventDispatcher()->dispatch($eventName, $event);

        return new JsonResponse([
            'code' => 'ok',
            'data' => []
        ]);
    }
} 