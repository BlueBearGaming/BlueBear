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
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\GameCreateRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            $initAction = new GameAction();
            $initAction->setName(EngineEvent::ENGINE_GAME_COMBAT_INIT);
            $initAction->setAction(EngineEvent::ENGINE_GAME_COMBAT_INIT);
            $initAction->setGame($game);
            $initAction->setData(json_encode([
                'gameId' => $game->getId(),
                'contextId' => $game->getContext()->getId()
            ]));

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
            $entityInstance1 = $this
                ->get('bluebear.manager.entity_instance')
                ->create($game->getContext(), $fighter2, new Position(0, 1), $layer);


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
        /** @var Game $game */
        $game = $this
            ->get('bluebear.manager.game')
            ->find($request->get('gameId'));
        $actionStack = $game->getActionStack();
        /** @var GameAction $action */
       foreach ($actionStack as $action) {
            $event = $this
                ->get('bluebear.engine.engine')
                ->run($action->getAction(), $action->getData());
            var_dump($event->getResponse());
        }
        return [];
    }
}
