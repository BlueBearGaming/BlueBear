<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Map\Player;

class GameManager
{
    use ManagerTrait;

    public function create()
    {
        $game = new Game();
        $game->setHash(uniqid('game_'));
        $this->save($game);

        // TODO create user player and IA player
        $player = new Player();
        $player->setIsHuman(true);

        $player = new Player();
        $player->setIsHuman(false);

        return $game;
    }

    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Game');
    }
}
