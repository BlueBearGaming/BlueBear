<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\CoreBundle\Entity\Map\Army;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Utils\Position;
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
use BlueBear\EngineBundle\Event\Request\GameCreateRequest;
use BlueBear\EngineBundle\Event\Request\GameTurnRequest;
use BlueBear\EngineBundle\Event\Response\CombatResponse;
use BlueBear\EngineBundle\Event\Sub\FightersList;
use BlueBear\EngineBundle\Manager\EntityInstanceManager;
use Doctrine\ORM\EntityManager;
use Exception;
use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class GameSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::HELL_ARENA_GAME_CREATE => 'onGameArenaCreate',
            EngineEvent::ENGINE_GAME_COMBAT_INIT => 'onCombatInit',
            EngineEvent::ENGINE_GAME_TURN => 'onGameTurn',
            EngineEvent::ENGINE_GAME_COMBAT_ATTACK => 'onCombatAttack',
            EngineEvent::ENGINE_GAME_END_OF_TURN => 'onEndOfTurn'
        ];
    }

    public function onGameArenaCreate(GameEvent $event)
    {
        /** @var GameCreateRequest $request */
        $request = $event->getRequest();
        $serializer = $this->get('serializer');
        $gameManager = $this
            ->container
            ->get('bluebear.manager.game');
        // creating game object
        $game = $gameManager->create();
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        // get entity instances by id
        $armyManager = $this->container->get('bluebear.manager.army');
        $positionY = 0;
        // dump map and dump layer for hell arena
        $layer = $this->getDumbLayer();
        $this->getDumpMap($game);
        // creating init combat action. It will creates fighters actions according to the game rules
        $actionData = new CombatInitData();
        $actionData->gameId = $game->getId();
        $actionData->contextId = $game->getContext()->getId();
        $actionData->fightersByPlayer = [];

        foreach ($request->fightersIdsByPlayer as $playerId => $fightersIds) {
            // get player
            $player = $entityManager
                ->getRepository('BlueBearCoreBundle:Map\Player')
                ->find($playerId);
            // TODO check if player is allowed
            if (!$player) {
                throw new Exception("Invalid player id {$playerId} for fighters " . implode(', ', $fightersIds));
            }
            // find arena army
            $arenaArmy = $entityManager
                ->getRepository('BlueBearCoreBundle:Map\Army')
                ->findOneBy([
                    'player' => $playerId,
                    'name' => 'test_army_arena'
                ]);
            // reset arena army if required
            if ($arenaArmy) {
                $armyManager->reset($arenaArmy);
            } else {
                $arenaArmy = $armyManager->create('test_army_arena', $player);
            }
            $actionData->fightersByPlayer[$playerId] = new FightersList();
            $positionX = 0;
            // create fighter instance from model, and it to the current player army
            foreach ($fightersIds as $fighterId) {
                $fighterModel = $entityManager
                    ->getRepository('BlueBearEngineBundle:EntityModel')
                    ->find($fighterId);
                $fighterInstance = $this
                    ->get('bluebear.manager.entity_instance')
                    ->create($game->getContext(), $fighterModel, new Position($positionX, $positionY), $layer, $arenaArmy);
                $instances[] = $fighterInstance;
                $actionData->fightersByPlayer[$playerId]->entityInstances[] = $fighterInstance;
                $positionX++;
            }
            $positionY++;
        }
        $initAction = new GameAction();
        $initAction->setName(EngineEvent::ENGINE_GAME_COMBAT_INIT);
        $initAction->setAction(EngineEvent::ENGINE_GAME_COMBAT_INIT);
        $initAction->setGame($game);
        $initAction->setData($serializer->serialize($actionData, 'json'));
        // saving combat init action
        $this
            ->get('doctrine')
            ->getManager()
            ->persist($initAction);
        $game->addActionToStack($initAction);
        // TODO game save useful here ?
        $this
            ->get('bluebear.manager.game')
            ->save($game);
        // set current game for next event subscriber
        $event->setGame($game);
    }

    public function onCombatInit(GameEvent $event)
    {
        /** @var CombatRequest $request */
        $request = $event->getRequest();
        /** @var CombatResponse $response */
        $response = $event->getResponse();
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        if (!$request->gameId) {
            throw new Exception('You should provide a gameId');
        }
        // get first action from stack which should contains combat init data
        $actionManager = $this
            ->getContainer()
            ->get('bluebear.manager.game_action');
        $action = $actionManager->findFirst($request->gameId);
        $fighters = [];
        /** @var CombatRequest $data */
        $data = $serializer->deserialize($action->getData(), 'BlueBear\EngineBundle\Event\Request\CombatRequest', 'json');

        /**
         * @var  $playerId
         * @var FightersList $fighters
         */
        foreach ($data->fightersByPlayer as $playerId => $fighters) {
            foreach ($fighters->entityInstances as $fighter) {
                var_dump($fighter);
                var_dump($fighters);
                $entityInstance = $this
                    ->getContainer()
                    ->get('bluebear.manager.entity_instance')
                    ->find($fighter->getId());
                $entityInstance;
            }
        }
        $max = null;

        // TODO make this rule with rule engine
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

    protected function getDumbLayer()
    {
        $layer = $this
            ->container
            ->get('bluebear.manager.layer')
            ->findOneBy([
                'name' => 'test_layer_arena'
            ]);

        if (!$layer) {
            // create dumb layer for hell arena
            $layer = new Layer();
            $layer->setName('test_layer_arena');
            $layer->setLabel('Test Layer for Hell Arena');
            $layer->setType(Constant::LAYER_TYPE_UNIT);
            $this
                ->container
                ->get('bluebear.manager.layer')
                ->save($layer);
        }
        return $layer;
    }

    protected function getDumpMap(Game $game)
    {
        $map = $this
            ->container
            ->get('bluebear.manager.map')
            ->findOneBy([
                'name' => 'test_map_arena'
            ]);
        if (!$map) {
            // in arena, map is just an entity instance container
            $map = new Map();
            $map->setName('test_map_arena');
            $map->setLabel('Test Map for Arena');
            $map->setLayers([
                $this->getDumbLayer()
            ]);
            $map->setCellSize(1);
            $map->setContexts([
                $game->getContext()
            ]);
            $map->setType(Map::TYPE_SQUARE);
        }
        $game
            ->getContext()
            ->setMap($map);
        $this
            ->get('bluebear.manager.map')
            ->saveMap($map);

        return $map;
    }
}
