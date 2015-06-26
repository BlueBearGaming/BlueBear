<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\CombatRequest;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_GAME_CREATE => 'onGameCreate',
            EngineEvent::ENGINE_GAME_COMBAT_INIT => 'onCombatInit'
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

        if (!$request->gameId) {
            throw new Exception('You should provide a gameId');
        }
        /** @var GameAction[] $actionStack */
        $actionStack = $this
            ->getContainer()
            ->get('bluebear.manager.game_action')
            ->findBy([
                'game' => $request->gameId
            ]);

        // first is current action, we should remove it from the stack
        //$currentAction = $actionStack->first();

        $entityInstances = $this
            ->getContainer()
            ->get('bluebear.manager.entity_instance')
            ->findAll();
//        $fighter2 = $this
//            ->getContainer()
//            ->get('bluebear.manager.entity_instance')
//            ->find(35);
        /** @var EntityInstance[] $fighters */
        $fighters = [array_pop($entityInstances), array_pop($entityInstances)];
        $max = null;

        foreach ($fighters as $fighter) {
            var_dump($fighter->get('dexterity'));
        }

        //var_dump($fighters);
        //var_dump($fighter2);

        $action = new GameAction();




    }
}
