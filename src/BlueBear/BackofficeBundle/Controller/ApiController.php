<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\EngineBundle\Event\EngineEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm('engine_event_test');
        $snippets = $this->getJsonSnippets();

        return [
            'form' => $form->createView(),
            'snippets' => $snippets
        ];
    }

    protected function getJsonSnippets()
    {
        $events = EngineEvent::getAllowedEvents();
        $snippets = [];

        foreach ($events as $event) {
            $snippet = new \stdClass();
            if ($event == EngineEvent::ENGINE_ON_MAP_LOAD) {
                $snippet->mapId = 1;
                $snippets[$event] = $snippet;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_SAVE) {
                $snippet->mapId = 1;
                $snippets[$event] = $snippet;
            }
        }
        return json_encode($snippets, JSON_PRETTY_PRINT);
    }
} 