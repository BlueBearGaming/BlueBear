<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Event\Data\CombatData;
use BlueBear\EngineBundle\Event\Data\CombatInitData;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\EventRequest;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\CombatRequest;
use BlueBear\EngineBundle\Event\Request\GameCreateRequest;
use BlueBear\EngineBundle\Event\Response\CombatResponse;
use BlueBear\EngineBundle\Event\Response\ErrorResponse;
use BlueBear\EngineBundle\Event\Response\GameTurnResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CombatController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $characters = $this
            ->getEntityManager()
            ->getRepository('BlueBearEngineBundle:EntityModel')
            ->findAll();

        if (count($characters) == 0) {
            $this->addFlash('info', 'You should create characters first');
            return $this->redirectToRoute('bluebear.dungeon.selectRace');
        }
        $sortedCharacters = [];
        /** @var EntityModel $character */
        foreach ($characters as $character) {
            $sortedCharacters[$character->getId()] = $character->getLabel();
        }
        $form = $this->createForm(new CombatType(), null, [
            'entities' => $sortedCharacters
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $eventRequest = new GameCreateRequest();
            $gameEvent = new GameEvent($eventRequest);
            $this
                ->get('event_dispatcher')
                ->dispatch(EngineEvent::ENGINE_GAME_CREATE, $gameEvent);

            $game = $gameEvent->getGame();


            $data = $form->getData();
            $fighter1 = $this
                ->getEntityManager()
                ->getRepository('BlueBearEngineBundle:EntityModel')
                ->find($data['fighter_1']);
            $fighter2 = $this
                ->getEntityManager()
                ->getRepository('BlueBearEngineBundle:EntityModel')
                ->find($data['fighter_2']);

            $layer = new Layer();
            $layer->setName('TEST');
            $layer->setType(Constant::LAYER_TYPE_UNIT);
            $this
                ->getEntityManager()
                ->persist($layer);
            $this
                ->getEntityManager()
                ->persist($layer);
            $this
                ->getEntityManager()
                ->flush($layer);

            $map = new Map();
            $map->setName('testlolpandabite');
            $map->setLayers([
                $layer
            ]);
            $map->setCellSize(1);
            $map->setContexts([
                $game->getContext()
            ]);
            $map->setType(Map::TYPE_SQUARE);
            $game->getContext()->setMap($map);
            $this->get('bluebear.manager.map')->saveMap($map);

            $entityInstance1 = $this
                ->get('bluebear.manager.entity_instance')
                ->create($game->getContext(), $fighter1, new Position(0, 0), $layer);
            $entityInstance2 = $this
                ->get('bluebear.manager.entity_instance')
                ->create($game->getContext(), $fighter2, new Position(0, 1), $layer);
            $initAction = new GameAction();
            $initAction->setName(EngineEvent::ENGINE_GAME_COMBAT_INIT);
            $initAction->setAction(EngineEvent::ENGINE_GAME_COMBAT_INIT);
            $initAction->setGame($game);

            $actionData = new CombatInitData();
            $actionData->gameId = $game->getId();
            $actionData->contextId = $game->getContext()->getId();
            $actionData->fightersIds = [
                $entityInstance1->getId(),
                $entityInstance2->getId()
            ];
            $serializer = $this->get('serializer');
            $initAction->setData($serializer->serialize($actionData, 'json'));
            $this
                ->get('doctrine')
                ->getManager()
                ->persist($initAction);
            $game->addActionToStack($initAction);
            $this
                ->get('bluebear.manager.game')
                ->save($game);

            return $this->redirectToRoute('bluebear.dungeon.combat.run', [
                'gameId' => $game->getId()
            ]);
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function runAction(Request $request)
    {
        // get first action from the stack
        $currentAction = $this
            ->get('bluebear.manager.game_action')
            ->findFirst($request->get('gameId'));
        $this->forward404Unless($currentAction, 'Action not found for game ' . $request->get('gameId'));
        $event = $this
            ->get('bluebear.engine.engine')
            ->run($currentAction->getAction(), $currentAction->getData());
        $eventResponse = $event->getResponse();

        if ($eventResponse instanceof ErrorResponse) {
            print_r($eventResponse);
            die;
        }

        if ($request->isXmlHttpRequest()) {
            if ($event->getName() == EngineEvent::ENGINE_GAME_TURN) {
                /** @var CombatData $data */
                $data = $eventResponse->getData();
                $data['content'] = $this->renderView('@BlueBearDungeon/Combat/turn.html.twig', [
                    'data' => $data
                ]);
                $eventResponse->setData($data);
            }
            $serializer = $this->get('jms_serializer');
            $content = $serializer->serialize($eventResponse, 'json');
            $response = new Response();
            $response->setStatusCode(200);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }
        return [
            'response' => $eventResponse,
            'eventData' => $eventResponse->getData()
        ];
    }
}
