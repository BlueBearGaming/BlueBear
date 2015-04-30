<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Event\Request\MapUpdateRequest;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\MapItemClickRequest;
use BlueBear\EngineBundle\Event\Request\MapLoadRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\LoadContextSubRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\MapItemSubRequest;
use BlueBear\EngineBundle\Event\Request\SubRequest\UserContextSubRequest;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Event\Entity\PutEntityRequest;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ApiController
 *
 * Controller for api testing purpose
 */
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
        // initialize entity types
        $this->getContainer()->get('bluebear.game.entity_type_factory');

        if (!$map) {
            $this->setMessage('You should create a map before calling api', 'error');
            return $this->redirect('@bluebear_admin_map_list');
        }
        $snippets = $this->getJsonSnippets($map);

        return [
            'form' => $form->createView(),
            'snippets' => $snippets
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function scenarioAction(Request $request)
    {
        $maps = $this
            ->get('bluebear.manager.map')
            ->findAll();
        $scenario1 = $this->createForm('engine_scenario_test', null, [
            'step' => 1,
            'maps' => $maps
        ]);
        $scenario1->handleRequest($request);

        if ($scenario1->isValid()) {
            $step = $scenario1->getData()['step'];
            $scenario1 = $this->createForm('engine_scenario_test', null, [
                'step' => (int)$step + 1,
                'maps' => $maps
            ]);
            $scenario1->handleRequest($request);
        }
        return [
            'scenario1' => $scenario1->createView()
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
        $events = $this->get('bluebear.engine.engine')->getAllowedEvents();
        $snippets = [];
        /**
         * @var Context $context
         * @var Serializer $serializer
         */
        $context = $map->getContexts()->first();
        $serializer = $this->get('jms_serializer');

        foreach ($events as $event) {
            if ($event == EngineEvent::ENGINE_MAP_LOAD) {
                $snippets[$event] = $this->getMapLoadRequest($context);
            } else if ($event == EngineEvent::ENGINE_MAP_ITEM_CLICK) {
                // get MapItemClick request
                $snippets[$event] = $this->getMapItemClickRequest($map, $context);
            } else if ($event == EngineEvent::ENGINE_MAP_ITEM_MOVE) {
                // get MapItemMove request
                $snippets[$event] = $this->getMapItemMoveRequest($context);
            } else if ($event == EngineEvent::EDITOR_MAP_PUT_ENTITY) {
                $snippets[$event] = $this->getPutEntityRequest($map, $context);
            } else if ($event == EngineEvent::EDITOR_MAP_UPDATE) {
                $snippets[$event] = $this->getMapUpdateRequest($map, $context);
            }
        }
        return $serializer->serialize($snippets, 'json');
    }

    protected function getMapLoadRequest(Context $context)
    {
        $request = new MapLoadRequest();
        $request->contextId = $context->getId();
        $request->loadContext = new LoadContextSubRequest();
        $request->loadContext->bottomRight = new Position(20, 20);
        $request->loadContext->topLeft = new Position(-20, -20);
        $request->userContext = new UserContextSubRequest();
        $request->userContext->viewCenter = new Position(0, 0);

        return $request;
    }

    protected function getMapItemClickRequest(Map $map, Context $context)
    {
        /** @var PencilSet $pencilSet */
        $pencilSet = $map->getPencilSets()->first();
        // event request
        $request = new MapItemClickRequest();
        $request->contextId = $context->getId();
        $request->target = new MapItemSubRequest();
        $request->target->position = new Position(0, 0);

        if ($pencilSet && $pencilSet->getPencils()->count()) {
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
                $layer = $layers[array_rand($layers)];
                $request->target->layer = $layer->getName();
            } else {
                $this->addFlash('warning', 'No layer was found. Try to create at least one');
            }
        } else {
            $this->addFlash('warning', 'No pencil set was found. Try to create at least one');
        }
        return $request;
    }

    protected function getMapItemMoveRequest(Context $context)
    {
        // event request
        $request = new MapItemClickRequest();
        $request->contextId = $context->getId();
        $request->source = new MapItemSubRequest();
        $request->source->position = new Position(0, 0);
        $request->source->layer = 'units';
        $request->target = new MapItemSubRequest();
        $request->target->position = new Position(0, 1);
        $request->target->layer = 'land';
        return $request;
    }

    protected function getPutEntityRequest(Map $map, Context $context)
    {
        $request = null;
        $entities = $this->get('bluebear.manager.entity_model')->findAll();
        $layers = [];

        if ($entities) {
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
            $request->layerName = $this->getRandomLayer($layers)->getName();
            $request->x = 4;
            $request->y = 2;
        } else {
            $this->addFlash('warning', 'You have no entity model configured. PutEntity event is not available');
        }
        return $request;
    }

    protected function getMapUpdateRequest(Map $map, Context $context)
    {
        $request = new MapUpdateRequest();
        $request->contextId = $context->getId();
        $request->mapItems = [];
        $i = 0;

        while ($i < 3) {
            $subRequest = new MapItemSubRequest();
            $subRequest->x = rand(0, 10);
            $subRequest->y = rand(0, 10);
            $subRequest->layerName = $this->getRandomLayer($map->getLayers()->toArray())->getName();
            $subRequest->pencilName = $this->getRandomPencil($map->getPencilSets()->toArray())->getName();
            $request->mapItems[] = $subRequest;
            $i++;
        }
        return $request;
    }

    /**
     * @param Layer[] $layers
     * @return Layer
     */
    protected function getRandomLayer(array $layers)
    {
        return $layers[array_rand($layers)];
    }

    /**
     * @param PencilSet[] $pencilSets
     * @return Pencil
     */
    protected function getRandomPencil(array $pencilSets)
    {
        $pencil = new Pencil();
        $pencils = [];

        foreach ($pencilSets as $pencilSet) {
            $pencils = array_merge($pencilSet->getPencils()->toArray(), $pencils);
        }
        // pencil set can have no pencil
        if (count($pencils)) {
            $pencil = $pencils[array_rand($pencils)];
        }
        return $pencil;
    }

    /**
     * @return MapManager
     */
    public function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
} 
