<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
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
        $map = $this->getMapManager()->findOne();

        if (!$map) {
            $this->setMessage('You should create a map before calling api', 'error');
            return $this->redirect('@bluebear_backoffice_map');
        }
        $snippets = $this->getJsonSnippets($map);

        return [
            'form' => $form->createView(),
            'snippets' => $snippets
        ];
    }

    /**
     * Return json snippets to help user to make a request to api
     *
     * @param Map $map
     * @return string
     */
    protected function getJsonSnippets(Map $map)
    {
        $events = EngineEvent::getAllowedEvents();
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
                    // if map has pencil, we choose one
                    if (count($pencils)) {
                        /** @var Pencil $pencil */
                        $pencil = $pencils[array_rand($pencils)];
                        /** @var Layer $layer */
                        $layer = $map->getLayers()[array_rand($map->getLayers()->toArray())];

                        $mapItem = new stdClass();
                        $mapItem->x = 0;
                        $mapItem->y = 0;
                        $mapItem->pencil = $pencil->toArray();
                        $mapItem->layer = $layer->toArray();
                        $snippet->context->mapItems[] = $mapItem;
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