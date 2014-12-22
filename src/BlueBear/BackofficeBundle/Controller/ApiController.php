<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Entity\Map\Tile;
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
                $snippet->mapId = $map->getId();
                $snippet->contextId = null;
                $snippets[$event] = $snippet;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_SAVE) {
                $snippet->mapId = $map->getId();
                $snippet->context = new stdClass();

                if ($map->getCurrentContext()) {
                    $snippet->context->id = $map->getCurrentContext()->getId();
                    $snippet->context->tiles = [];
                    $tiles[] = $map->getCurrentContext()->getTiles()[0];
                    $tiles[] = $map->getCurrentContext()->getTiles()[1];

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
                        /** @var Tile $tile */
                        foreach ($tiles as $tile) {
                            $pencilObject = new stdClass();
                            $pencilObject->id = $pencil->getId();
                            $pencilObject->label = $pencil->getLabel();
                            $pencilObject->name = $pencil->getName();
                            /** @var Layer $layer */
                            $layer = $layers[array_rand($layers)];
                            $pencilObject->layer = $layer->getId();

                            $tileObject = new stdClass();
                            $tileObject->id = $tile->getId();
                            $tileObject->pencil = $pencilObject;
                            $snippet->context->tiles[$tile->getId()] = $tileObject;
                        }
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