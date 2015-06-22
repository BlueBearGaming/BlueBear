<?php

namespace BlueBear\EngineBundle\Event;

use BlueBear\CoreBundle\Entity\Game\Game;

class GameEvent extends EngineEvent
{
    /**
     * @var Game
     */
    protected $game;

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }
}
