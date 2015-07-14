<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\DungeonBundle\Entity\CharacterClass\Attack;
use BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Event\Data\CombatData;
use BlueBear\EngineBundle\Event\Data\CombatInitData;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\AttackRequest;
use BlueBear\EngineBundle\Event\Request\CombatRequest;
use BlueBear\EngineBundle\Event\Request\GameTurnRequest;
use BlueBear\EngineBundle\Event\Response\CombatResponse;
use BlueBear\EngineBundle\Manager\EntityInstanceManager;
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
            EngineEvent::ENGINE_GAME_COMBAT_ATTACK => 'onCombatAttack',
            EngineEvent::ENGINE_GAME_END_OF_TURN => 'onEndOfTurn'
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

        foreach ($data->fightersIds as $entityInstanceId) {
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
        /** @var Game $game */
        $game = $this
            ->get('bluebear.manager.game')
            ->find($request->gameId);
        /**
         * @var int $turn
         * @var EntityInstance $fighter
         */
        foreach ($fighters as $turn => $fighter) {
            $this->createTurn($fighter, $fighters, $turn, $game);
        }
        /** @var RouterInterface $router */
        $router = $this->get('router');
        $responseData = new CombatInitData();
        $responseData->contextId = $game->getContext()->getId();
        $responseData->gameId = $game->getId();
        $response->setData($data);
        // TODO only in debug mode
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
        $serializer = $this
            ->container
            ->get('serializer');
        $entityInstanceManager = $this->container->get('bluebear.manager.entity_instance');
        $json = $action->getData();
        $actionData = new CombatData();
        /** @var GameTurnRequest $data */
        $data = $serializer->deserialize($json, 'BlueBear\EngineBundle\Event\Request\GameTurnRequest', 'json');
        $actionData->source = $entityInstanceManager->find($data->sourceId);

        foreach ($data->targetsIds as $targetId) {
            $actionData->targets[] = $entityInstanceManager->find($targetId);
        }
        $actionData->gameId = $data->gameId;
        $actionData->contextId = $data->contextId;
        $actionData->attacks = $data->attacks;

        $event->getResponse()->setData($actionData);
    }

    public function onCombatAttack(GameEvent $event)
    {
        /** @var AttackRequest $request */
        $request = $event->getRequest();
        /** @var EntityInstanceManager $entityInstanceManager */
        $entityInstanceManager = $this->get('bluebear.manager.entity_instance');
        /** @var EntityInstance $attacker */
        $attacker = $entityInstanceManager->find($request->attackerId);
        /** @var EntityInstance $defender */
        $defender = $entityInstanceManager->find($request->defenderId);
        /** @var CharacterClass $attackerClass */
        $attackerClass = $this
            ->getContainer()
            ->get('bluebear.engine.unit_of_work')
            ->load(new EntityReference(CharacterClass::class, $attacker->get('class')));

        if (!$attackerClass->attacks->has($request->attack)) {
            throw new Exception("Invalid attack {$request->attack} for class {$attacker->get('class')}");
        }
        /** @var Attack $attack */
        $attack = $attackerClass->attacks->get($request->attack);
        $defenderHp = (int)$defender->get('hit_points');
        $defenderHp -= (int)$attack->damage;
        $defender->set('hit_points', $defenderHp);

        if ($defenderHp < 0) {
            $defender->set('status', 'dead');
        }
        $entityInstanceManager->save($defender);
        $entityInstanceManager->flush();

        $endOfTurnRequest = new GameTurnRequest();
        $endOfTurnRequest->contextId = $request->contextId;
        $endOfTurnRequest->gameId = $request->gameId;
        $endOfTurnRequest->sourceId = $request->attackerId;
        $endOfTurnRequest->targetsIds = [
            $defender->getId()
        ];

        $endOfTurnEvent = new GameEvent();
        $endOfTurnEvent->setName(EngineEvent::ENGINE_GAME_END_OF_TURN);
        $endOfTurnEvent->setRequest($endOfTurnRequest);

        $this
            ->container
            ->get('event_dispatcher')
            ->dispatch(EngineEvent::ENGINE_GAME_END_OF_TURN, $endOfTurnEvent);

    }

    public function onEndOfTurn(GameEvent $event)
    {
        /** @var GameTurnRequest $request */
        $request = $event->getRequest();
        /** @var GameAction $action */
        $action = $this
            ->get('bluebear.manager.game_action')
            ->findFirst($request->gameId);
        $this->get('bluebear.manager.game_action')->delete($action);
        /** @var EntityInstance $source */
        $source = $this
            ->container
            ->get('bluebear.manager.entity_instance')
            ->find($request->sourceId);
        $targets = [];

        foreach ($request->targetsIds as $targetId) {
            $targets[] = $this
                ->container
                ->get('bluebear.manager.entity_instance')
                ->find($targetId);
        }
        /** @var Game $game */
        $game = $this
            ->container
            ->get('bluebear.manager.game')
            ->find($request->gameId);
        // TODO handle life status with HOA Ruler
        if ($source->get('status') != 'dead') {
            $this->createTurn($source, $targets, (int)$request->turn + 1, $game);
        }
    }

    protected function createTurn(EntityInstance $fighter, $fighters, $turn, Game $game)
    {
        $serializer = $this
            ->getContainer()
            ->get('serializer');
        $unitOfWork = $this
            ->getContainer()
            ->get('bluebear.engine.unit_of_work');
        /** @var CharacterClass $class */
        $class = $unitOfWork->load(new EntityReference(CharacterClass::class, $fighter->get('class')));
        $actionData = new GameTurnRequest();
        $actionData->turn = $turn;
        $actionData->gameId = $game->getId();
        $actionData->contextId = $game->getContext()->getId();
        $actionData->sourceId = $fighter->getId();
        $actionData->attacks = $class->attacks->getValues();
        /** @var EntityInstance $currentFighter */
        foreach ($fighters as $currentFighter) {
            if ($currentFighter->getId() != $fighter->getId()) {
                $actionData->targetsIds[] = $currentFighter->getId();
            }
        }
        $action = new GameAction();
        $action->setName("game : {$game->getId()}, turn: {$turn}, entity: {$fighter->getId()}, {$fighter->getName()}");
        $action->setAction(EngineEvent::ENGINE_GAME_TURN);
        $action->setData($serializer->serialize($actionData, 'json'));
        $action->setGame($game);
        $action->setEntityInstance($fighter);

        $this
            ->container
            ->get('bluebear.manager.entity_instance')
            ->save($action);
    }
}
