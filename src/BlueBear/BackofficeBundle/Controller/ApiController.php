<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    /**
     * @return string
     */
    protected function getJsonSnippets()
    {
        $events = EngineEvent::getAllowedEvents();
        $map = $this->getMapManager()->findOne();
        $snippets = [];

        foreach ($events as $event) {
            $snippet = new stdClass();
            if ($event == EngineEvent::ENGINE_ON_MAP_LOAD) {
                $snippet->mapId = $map->getId();
                $snippets[$event] = $snippet;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_SAVE) {
                $snippet->mapId = $map->getId();
                $snippet->context = new stdClass();

                if ($map->getCurrentContext()) {
                    $snippet->context->id = $map->getCurrentContext()->getId();
                    $snippet->context->tiles = [];
                    $snippet->context->tiles[] = $map->getCurrentContext()->getTiles()[0];
                    $snippet->context->tiles[] = $map->getCurrentContext()->getTiles()[1];
                }
                $snippets[$event] = $snippet;
            }
        }
        return json_encode($snippets, JSON_PRETTY_PRINT);
    }

    /**
     * @return MapManager
     */
    public function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
} 