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
        $this->save($game);

        return $game;
    }

    public function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Game');
    }
}
