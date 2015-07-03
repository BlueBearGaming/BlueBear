<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\AttackRequest;
use BlueBear\EngineBundle\Event\Request\CombatRequest;
use BlueBear\EngineBundle\Event\Response\CombatResponse;
use BlueBear\EngineBundle\Event\Response\GameTurnResponse;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class GameSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_GAME_CREATE => 'onGameCreate',
            EngineEvent::ENGINE_GAME_COMBAT_INIT => 'onCombatInit',
            EngineEvent::ENGINE_GAME_TURN => 'onGameTurn',
            EngineEvent::ENGINE_GAME_COMBAT_ATTACK => 'onCombatAttack'
        ];
    }

    public function onGameCreate(GameEvent $event)
    {
        $gameManager = $this
            ->container
            ->get('bluebear.manager.game');
        // creating game object
        $game = $gameManager->create();
        $event->setGame($game);
    }

    public function onCombatInit(GameEvent $event)
    {
        /** @var CombatRequest $request */
        $request = $event->getRequest();
        /** @var CombatResponse $response */
        $response = $event->getResponse();

        if (!$request->gameId) {
            throw new Exception('You should provide a gameId');
        }
        $actionManager = $this
            ->getContainer()
            ->get('bluebear.manager.game_action');
        $action = $actionManager->findFirst($request->gameId);
        $fighters = [];
        /** @var CombatRequest $data */
        $data = json_decode($action->getData());

        foreach ($data->entityInstanceIds as $entityInstanceId) {
            $entityInstance = $this
                ->getContainer()
                ->get('bluebear.manager.entity_instance')
                ->find($entityInstanceId);
            $fighters[] = $entityInstance;
        }
        $max = null;

        usort($fighters, function ($entityInstance1, $entityInstance2) {
            /**
             * @var EntityInstance $entityInstance1
             * @var EntityInstance $entityInstance2
             */
            return (int)$entityInstance1->get('dexterity') < (int)$entityInstance2->get('dexterity') ? 1 : -1;
        });
        $game = $this
            ->get('bluebear.manager.game')
            ->find($request->gameId);

        foreach ($fighters as $turn => $fighter) {
            $data = new CombatRequest();
            $data->gameId = $request->gameId;
            $data->lockedEntityInstanceId = $fighter->getId();
            $data->turn = $turn;
            $action = new GameAction();
            $action->setName("game : {$request->gameId}, turn: {$turn}, entity: {$fighter->getId()}, {$fighter->getName()}");
            $action->setAction(EngineEvent::ENGINE_GAME_TURN);
            $action->setData(json_encode($data));
            $action->setGame($game);
            $actionManager->save($action);
        }
        /** @var RouterInterface $router */
        $router = $this->get('router');

        $response->setData($request->contextId, $request->gameId);
        $response->endPoint = $router->generate('bluebear_engine_trigger_event', [
            'eventName' => 'bluebear.game.turn'
        ]);
        $this
            ->get('bluebear.manager.game_action')
            ->removeFirst($request->gameId);
    }

    public function onGameTurn(GameEvent $event)
    {
        /** @var CombatRequest $request */
        $request = $event->getRequest();
        /** @var GameAction $action */
        $action = $this
            ->get('bluebear.manager.game_action')
            ->findFirst($request->gameId);
        /** @var CombatRequest $data */
        $data = json_decode($action->getData());
        /** @var EntityInstance $entityInstance */
        $entityInstance = $this
            ->get('bluebear.manager.entity_instance')
            ->find($data->lockedEntityInstanceId);
        /** @var CharacterClass $class */
        $class = $this
            ->getContainer()
            ->get('bluebear.engine.unit_of_work')
            ->load(new EntityReference(CharacterClass::class, $entityInstance->get('class')));
        /** @var Game $game */
        $game = $this
            ->getContainer()
            ->get('bluebear.manager.game')
            ->find($request->gameId);
        /** @var GameTurnResponse $response */
        $response = $event->getResponse();
        $response->setData([
            'entityInstance' => $entityInstance,
            'attacks' => $class->attacks->getValues(),
            'turn' => 50,
            'gameId' => $request->gameId,
            'contextId' => $game->getContext()->getId()
        ]);
    }

    public function onCombatAttack(GameEvent $event)
    {
        /** @var AttackRequest $request */
        $request = $event->getRequest();
        /** @var EntityInstance $attacker */
        $attacker = $this
            ->get('bluebear.manager.entity_instance')
            ->find($request->attackerId);
        $attacks = $this
            ->getContainer()
            ->get('bluebear.engine.unit_of_work')
            ->load(new EntityReference(CharacterClass::class, $attacker->get('class')));

    }
}
