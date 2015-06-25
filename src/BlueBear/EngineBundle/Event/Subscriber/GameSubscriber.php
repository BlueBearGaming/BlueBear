<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameSubscriber implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_GAME_CREATE => 'onGameCreate',
            EngineEvent::ENGINE_GAME_COMBAT_INIT => 'onCombatInit',
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
//        $initAction = $event
//            ->getGame()
//            ->getActionStack();

        $actionStack = $this
            ->getContainer()
            ->get('bluebear.manager.game_action')
            ->findBy([
                'game' => $event->getGame()
            ]);




        var_dump($event);

        $action = new GameAction();



        die('init action stack');
    }
}
