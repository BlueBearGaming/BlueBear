<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Game\Game;

class GameManager
{
    use ManagerTrait;

    public function create()
    {
        $game = new Game();
        $game->setHash(uniqid('game_'));
        $game->setName('My favourite game');
        $this->save($game);

        // TODO create user player and IA player
//        $player = new Player();
//        $player->setIsHuman(true);
//
//        $player = new Player();
//        $player->setIsHuman(false);

        return $game;
    }

    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Game\Game');
    }
}
