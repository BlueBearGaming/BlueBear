<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Map\LoadContextRequest;
use BlueBear\EngineBundle\Event\MapItem\MapItemClickRequest;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Event\Entity\PutEntityRequest;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{
    use ControllerTrait;

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
                        if ($pencil and $pencil->isLayerTypeAllowed($layer->getType())) {
                            $layers[] = $layer;
                        }
                    }
                    if ($pencil and count($layers)) {
                        $request->pencil = $pencil->getId();
                        $layer = $layers[array_rand($layers)];
                        $request->layer = $layer->getId();
                    } else {
                        $this->addFlash('warning', 'No layer was found. Try to create at least one');
                    }
                } else {
                    $this->addFlash('warning', 'No pencil set was found. Try to create at least one');
                }
                $snippets[$event] = $request;
            } else if ($event == EngineEvent::ENGINE_ON_MAP_PUT_ENTITY) {
                $entities = $this->get('bluebear.manager.entity_model')->findAll();

                if ($entities) {
                    $layers = [];
                    /** @var EntityModel $entityModel */
                    $entityModel = $entities[array_rand($entities)];

                    /** @var Layer $layer */
                    foreach ($map->getLayers() as $layer) {
                        if ($entityModel and $entityModel->isLayerTypeAllowed($layer->getType())) {
                            $layers[] = $layer;
                        }
                    }
                    // TODO sort to have only unit model instance and make an other event with items
                    $request = new PutEntityRequest();
                    $request->contextId = $context->getId();
                    $request->entityModelId = $entityModel->getId();
                    $request->layerId = $layers[array_rand($layers)]->getId();
                    $request->x = 4;
                    $request->y = 2;
                    $snippets[$event] = $request;
                } else {
                    $this->addFlash('warning', 'You have no entity model configured. PutEntity event is not available');
                }
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