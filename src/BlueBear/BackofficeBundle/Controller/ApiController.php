<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{
    use ControllerBehavior;

    /**
     * Display api interface to send event and receive response from api
     *
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
     * Return json snippets to help user to make a request to api
     *
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
                $snippet->mapName = $map->getName();
                $snippet->contextId = null;
                $snippets[$event] = $snippet;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_SAVE) {
                $snippet->mapName = $map->getName();
                $snippet->context = new stdClass();

                if ($map->getCurrentContext()) {
                    $snippet->context->id = $map->getCurrentContext()->getId();
                    $snippet->context->mapItems = [];
                    // get allowed pencils
                    $pencils = [];
                    $pencilSets = $map->getPencilSets();

                    /** @var PencilSet $pencilSet */
                    foreach ($pencilSets as $pencilSet) {
                        $pencils = array_merge($pencils, $pencilSet->getPencils()->toArray());
                    }
                    if (count($pencils)) {
                        /** @var Pencil $pencil */
                        $pencil = $pencils[0];
                        $layers = $map->getLayers()->toArray();
                        // @todo
                    }
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