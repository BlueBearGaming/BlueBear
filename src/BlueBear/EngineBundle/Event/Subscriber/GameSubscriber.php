<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
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
        die('init action stack');
    }
}
