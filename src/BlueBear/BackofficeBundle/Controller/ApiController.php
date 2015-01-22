<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Map\LoadContextRequest;
use BlueBear\EngineBundle\Event\MapItem\MapItemClickRequest;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        /**
         * @var Context $context
         * @var Serializer $serializer
         */
        $context = $map->getContexts()->first();
        $serializer = $this->get('jms_serializer');

        foreach ($events as $event) {
            if ($event == EngineEvent::ENGINE_ON_CONTEXT_LOAD) {
                $request = new LoadContextRequest();
                $request->contextId = $context->getId();
                $snippets[$event] = $request;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_ITEM_CLICK) {
                $pencilSet = $map->getPencilSets()->first();
                // event request
                $request = new MapItemClickRequest();
                $request->contextId = $context->getId();
                $request->x = 5;
                $request->y = 5;

                if ($pencilSet) {
                    /** @var Pencil $pencil */
                    $pencil = $pencilSet->getPencils()->first();
                    $layers = [];

                    /** @var Layer $layer */
                    foreach ($map->getLayers() as $layer) {
                        if ($pencil->isLayerTypeAllowed($layer->getType())) {
                            $layers[] = $layer;
                        }
                    }
                    if ($pencil) {
                        $request->pencil = $pencil->getId();
                        $layer = $layers[array_rand($layers)];
                        $request->layer =  $layer->getId();
                    } else {
                        $this->addFlash('warning', 'No layer was found. Try to create at least one');
                    }
                } else {
                    $this->addFlash('warning', 'No pencil set was found. Try to create at least one');
                }
                $snippets[$event] = $request;
            }
        }
        return $serializer->serialize($snippets, 'json');
    }

    /**
     * @return MapManager
     */
    public function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
} 